<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\AdminWalletRepositoryInterface;
use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\DeliveryManRepositoryInterface;
use App\Contracts\Repositories\OrderDetailRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\OrderTransactionRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Contracts\Repositories\VendorWalletRepositoryInterface;
use App\Enums\ViewPaths\Admin\Dashboard;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends BaseController
{
    public function __construct(
        private readonly AdminWalletRepositoryInterface      $adminWalletRepo,
        private readonly CustomerRepositoryInterface         $customerRepo,
        private readonly OrderTransactionRepositoryInterface $orderTransactionRepo,
        private readonly ProductRepositoryInterface          $productRepo,
        private readonly DeliveryManRepositoryInterface      $deliveryManRepo,
        private readonly OrderRepositoryInterface            $orderRepo,
        private readonly OrderDetailRepositoryInterface      $orderDetailRepo,
        private readonly BrandRepositoryInterface            $brandRepo,
        private readonly VendorRepositoryInterface           $vendorRepo,
        private readonly VendorWalletRepositoryInterface     $vendorWalletRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->dashboard();
    }

    public function dashboard(): View
    {
        $mostRatedProducts = $this->productRepo->getTopRatedList()->take(DASHBOARD_DATA_LIMIT);
        $topSellProduct = $this->productRepo->getTopSellList(relations: ['orderDetails'])->take(DASHBOARD_TOP_SELL_DATA_LIMIT);
        $topCustomer = $this->orderRepo->getTopCustomerList(relations: ['customer'], dataLimit: 'all')->take(DASHBOARD_DATA_LIMIT);
        $topRatedDeliveryMan = $this->deliveryManRepo->getTopRatedList(filters: ['seller_id' => 0], relations: ['deliveredOrders'], dataLimit: 'all')->take(DASHBOARD_DATA_LIMIT);
        $topVendorByEarning = $this->vendorWalletRepo->getListWhere(orderBy: ['total_earning' => 'desc'], relations: ['seller.shop'])->take(DASHBOARD_DATA_LIMIT);
        $topVendorByOrderReceived = $this->orderRepo->getTopVendorListByOrderReceived(relations: ['seller.shop'], dataLimit: 'all')->take(DASHBOARD_DATA_LIMIT);

        $from = Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');
        $inhouseEarningStatisticsData = $this->orderTransactionRepo->getEarningStatisticsData(
            sellerIs: 'admin',
            dataRange: ['from' => $from, 'to' => $to],
            groupBy: ['year', 'month']
        );
        $sellerEarningStatisticsData = $this->orderTransactionRepo->getEarningStatisticsData(
            sellerIs: 'seller',
            dataRange: ['from' => $from, 'to' => $to],
            groupBy: ['year', 'month']
        );

        $commissionEarningStatisticsData = $this->orderTransactionRepo->getCommissionEarningStatisticsData(
            dataRange: ['from' => Carbon::now()->startOfYear()->format('Y-m-d'), 'to' => Carbon::now()->endOfYear()->format('Y-m-d')],
            groupBy: ['year', 'month'],
        );

        $data = self::getOrderStatusData();
        $admin_wallet = $this->adminWalletRepo->getFirstWhere(params: ['admin_id' => 1]);

        $from = now()->startOfYear()->format('Y-m-d');
        $to = now()->endOfYear()->format('Y-m-d');
        $range = range(1,12);
        $label = ["Jan", "Feb", "Mar", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $inHouseOrderEarningArray = $this->getOrderStatisticsData(from:$from ,to: $to,range: $range,type:'month',userType:'admin');
        $vendorOrderEarningArray = $this->getOrderStatisticsData(from: $from, to: $to, range: $range,type:'month',userType:'seller');
        $dateType = 'yearEarn';
        $data += [
            'order' => $this->orderRepo->getListWhere(dataLimit: 'all')->count(),
            'brand' => $this->brandRepo->getListWhere(dataLimit: 'all')->count(),
            'topSellProduct' => $topSellProduct,
            'mostRatedProducts' => $mostRatedProducts,
            'topVendorByEarning' => $topVendorByEarning,
            'top_customer' => $topCustomer,
            'top_store_by_order_received' => $topVendorByOrderReceived,
            'topRatedDeliveryMan' => $topRatedDeliveryMan,
            'inhouse_earning' => $admin_wallet['inhouse_earning'] ?? 0,
            'commission_earned' => $admin_wallet['commission_earned'] ?? 0,
            'delivery_charge_earned' => $admin_wallet['delivery_charge_earned'] ?? 0,
            'pending_amount' => $admin_wallet['pending_amount'] ?? 0,
            'total_tax_collected' => $admin_wallet['total_tax_collected'] ?? 0,
            'getTotalCustomerCount' => $this->customerRepo->getList()->count(),
            'getTotalVendorCount' => $this->vendorRepo->getListWhere(dataLimit: 'all')->count(),
            'getTotalDeliveryManCount' => $this->deliveryManRepo->getListWhere(filters:['seller_id' => 0],dataLimit: 'all')->count(),
        ];
        return view(Dashboard::VIEW[VIEW], compact('data', 'inhouseEarningStatisticsData', 'sellerEarningStatisticsData', 'commissionEarningStatisticsData','inHouseOrderEarningArray','vendorOrderEarningArray','label','dateType'));
    }

    public function getOrderStatus(Request $request): JsonResponse
    {
        session()->put('statistics_type', $request['statistics_type']);
        $data = self::getOrderStatusData();
        return response()->json(['view' => view('admin-views.partials._dashboard-order-stats', compact('data'))->render()], 200);
    }

    public function getOrderStatusData(): array
    {
        $orderQuery = $this->orderRepo->getListWhere(dataLimit: 'all');
        $storeQuery = $this->vendorRepo->getListWhere(dataLimit: 'all');
        $productQuery = $this->productRepo->getListWhere(dataLimit: 'all');
        $customerQuery = $this->customerRepo->getListWhere(dataLimit: 'all');
        $totalSaleQuery = $this->orderDetailRepo->getListWhere(filters: ['delivery_status' => 'delivered']);
        $failedQuery = $this->orderRepo->getListWhere(filters: ['order_status' => 'failed'], dataLimit: 'all');
        $pendingQuery = $this->orderRepo->getListWhere(filters: ['order_status' => 'pending'], dataLimit: 'all');
        $returnedQuery = $this->orderRepo->getListWhere(filters: ['order_status' => 'returned'], dataLimit: 'all');
        $canceledQuery = $this->orderRepo->getListWhere(filters: ['order_status' => 'canceled'], dataLimit: 'all');
        $confirmedQuery = $this->orderRepo->getListWhere(filters: ['order_status' => 'confirmed'], dataLimit: 'all');
        $deliveredQuery = $this->orderRepo->getListWhere(filters: ['order_status' => 'delivered'], dataLimit: 'all');
        $processingQuery = $this->orderRepo->getListWhere(filters: ['order_status' => 'processing'], dataLimit: 'all');
        $outForDeliveryQuery = $this->orderRepo->getListWhere(filters: ['order_status' => 'out_for_delivery'], dataLimit: 'all');

        return [
            'order' => self::getCommonQueryOrderStatus($orderQuery),
            'store' => self::getCommonQueryOrderStatus($storeQuery),
            'failed' => self::getCommonQueryOrderStatus($failedQuery),
            'pending' => self::getCommonQueryOrderStatus($pendingQuery),
            'product' => self::getCommonQueryOrderStatus($productQuery),
            'customer' => self::getCommonQueryOrderStatus($customerQuery),
            'returned' => self::getCommonQueryOrderStatus($returnedQuery),
            'canceled' => self::getCommonQueryOrderStatus($canceledQuery),
            'confirmed' => self::getCommonQueryOrderStatus($confirmedQuery),
            'delivered' => self::getCommonQueryOrderStatus($deliveredQuery),
            'processing' => self::getCommonQueryOrderStatus($processingQuery),
            'total_sale' => self::getCommonQueryOrderStatus($totalSaleQuery),
            'out_for_delivery' => self::getCommonQueryOrderStatus($outForDeliveryQuery),
        ];
    }

    public function getCommonQueryOrderStatus($query)
    {
        $today = session()->has('statistics_type') && session('statistics_type') == 'today' ? 1 : 0;
        $this_month = session()->has('statistics_type') && session('statistics_type') == 'this_month' ? 1 : 0;

        return $query->when($today, function ($query) {
            return $query->where('created_at', '>=', now()->startOfDay())
                            ->where('created_at', '<', now()->endOfDay());
        })->when($this_month, function ($query) {
            return $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        })->count();
    }


    public function getEarningStatistics(Request $request): JsonResponse
    {
        $dateType = $request['type'];
        $inhouseLabel = [];
        $inhouseEarningStatisticsData = [];
        if ($dateType == 'yearEarn') {
            $inhouseLabel = ["Jan", "Feb", "Mar", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            $inhouseEarningStatisticsData = $this->orderTransactionRepo->getEarningStatisticsData(
                sellerIs: 'admin',
                dataRange: ['from' => Carbon::now()->startOfYear()->format('Y-m-d'), 'to' => Carbon::now()->endOfYear()->format('Y-m-d')],
                groupBy: ['year', 'month'],
            );
        } elseif ($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $inhouseLabel = range(1, date('d', strtotime($to)));
            $inhouseEarningStatisticsData = $this->orderTransactionRepo->getEarningStatisticsData(
                sellerIs: 'admin',
                dataRange: ['from' => $from, 'to' => $to],
                groupBy: ['day'],
                dateEnd: date('d', strtotime($to)),
            );
        } elseif ($dateType == 'WeekEarn') {
            $from = Carbon::now()->startOfWeek()->format('Y-m-d');
            $to = Carbon::now()->endOfWeek()->format('Y-m-d');
            $inhouseLabel = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $inhouseEarningStatisticsData = $this->orderTransactionRepo->getEarningStatisticsData(
                sellerIs: 'admin',
                dataRange: ['from' => $from, 'to' => $to],
                groupBy: ['day'],
                dateStart: date('d', strtotime($from)),
                dateEnd: date('d', strtotime($to)),
            );
        }

        $sellerLabel = [];
        $sellerEarningStatisticsData = [];
        if ($dateType == 'yearEarn') {
            $sellerLabel = ["Jan", "Feb", "Mar", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            $sellerEarningStatisticsData = $this->orderTransactionRepo->getEarningStatisticsData(
                sellerIs: 'seller',
                dataRange: ['from' => Carbon::now()->startOfYear()->format('Y-m-d'), 'to' => Carbon::now()->endOfYear()->format('Y-m-d')],
                groupBy: ['year', 'month'],
            );
        } elseif ($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $sellerLabel = range(1, $number);
            $sellerEarningStatisticsData = $this->orderTransactionRepo->getEarningStatisticsData(
                sellerIs: 'seller',
                dataRange: ['from' => $from, 'to' => $to],
                groupBy: ['day'],
                dateEnd: $number,
            );
        } elseif ($dateType == 'WeekEarn') {
            $from = Carbon::now()->startOfWeek()->format('Y-m-d');
            $to = Carbon::now()->endOfWeek()->format('Y-m-d');
            $sellerLabel = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $sellerEarningStatisticsData = $this->orderTransactionRepo->getEarningStatisticsData(
                sellerIs: 'seller',
                dataRange: ['from' => $from, 'to' => $to],
                groupBy: ['day'],
                dateStart: date('d', strtotime($from)),
                dateEnd: date('d', strtotime($to)),
            );
        }

        $commissionLabel = [];
        $commissionEarningStatisticsData = [];
        if ($dateType == 'yearEarn') {
            $commissionLabel = array("Jan", "Feb", "Mar", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
            $commissionEarningStatisticsData = $this->orderTransactionRepo->getCommissionEarningStatisticsData(
                dataRange: ['from' => Carbon::now()->startOfYear()->format('Y-m-d'), 'to' => Carbon::now()->endOfYear()->format('Y-m-d')],
                groupBy: ['year', 'month'],
            );
        } elseif ($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $commissionLabel = range(1, $number);
            $commissionEarningStatisticsData = $this->orderTransactionRepo->getCommissionEarningStatisticsData(
                dataRange: ['from' => $from, 'to' => $to],
                groupBy: ['day'],
                dateEnd: $number,
            );
        } elseif ($dateType == 'WeekEarn') {

            $from = Carbon::now()->startOfWeek()->format('Y-m-d');
            $to = Carbon::now()->endOfWeek()->format('Y-m-d');
            $commissionLabel = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $commissionEarningStatisticsData = $this->orderTransactionRepo->getCommissionEarningStatisticsData(
                dataRange: ['from' => $from, 'to' => $to],
                groupBy: ['day'],
                dateStart: date('d', strtotime($from)),
                dateEnd: date('d', strtotime($to)),
            );
        }

        $data = [
            'inhouse_label' => $inhouseLabel,
            'inhouse_earn' => array_values($inhouseEarningStatisticsData),
            'seller_label' => $sellerLabel,
            'seller_earn' => array_values($sellerEarningStatisticsData),
            'commission_label' => $commissionLabel,
            'commission_earn' => array_values($commissionEarningStatisticsData)
        ];
        return response()->json($data);
    }
    public function getOrderStatistics(Request $request):JsonResponse
    {
        $dateType = $request['type'];
        $from = null; $to = null; $type = null; $range = null;
        if ($dateType == 'yearEarn') {
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');
            $range = range(1, 12);
            $type = 'month';
            $keyRange = ["Jan", "Feb", "Mar", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        } elseif ($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $endRange = date('d', strtotime($to));
            $range = range(1, $endRange);
            $type = 'day';
            $keyRange = $range;
        } elseif ($dateType == 'WeekEarn') {

            $from = Carbon::now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
            $to = Carbon::now()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
            $range = ['Sunday','Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $type = 'day_of_week';
            $keyRange = $range;
        }

        $inHouseOrderEarningArray = $this->getOrderStatisticsData(from: $from, to: $to, range: $range, type: $type,userType:'admin');
        $vendorOrderEarningArray = $this->getOrderStatisticsData(from: $from, to: $to, range: $range, type: $type,userType:'seller');
        $label = $keyRange ?? [];
        $inHouseOrderEarningArray = array_values($inHouseOrderEarningArray);
        $vendorOrderEarningArray = array_values($vendorOrderEarningArray);
        return response()->json([
            'view' => view(Dashboard::ORDER_STATISTICS[VIEW], compact('inHouseOrderEarningArray','vendorOrderEarningArray','label','dateType'))->render(),
        ]);
    }
    protected function getOrderStatisticsData($from,$to,$range,$type,$userType):array
    {
        $orderEarnings = $this->orderRepo->getListWhereBetween(
            filters:  [
                'seller_is'=>$userType,
                'payment_status' => 'paid'
            ],
            selectColumn: 'order_amount',
            whereBetween: 'created_at',
            whereBetweenFilters: [$from, $to],
        );
        $orderEarningArray = [];
        foreach ($range as $value){
            $matchingEarnings = $orderEarnings->where($type, $value);
            if ($matchingEarnings->count() > 0) {
                $orderEarningArray[$value] = number_format($matchingEarnings->sum('sums'), 2, '.', '');
            } else {
                $orderEarningArray[$value] = 0;
            }
        }
        return $orderEarningArray;
    }
}
