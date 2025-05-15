<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Laralink">
    <!-- Site Title -->
    <title>Invoice | {{ getWebConfig('company_name') }}</title>
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/custom_invoice.css') }}">

</head>

<body>
    <div class="tm_container">
        <div class="tm_invoice_wrap" id="invoiceArea">
            <div class="tm_invoice tm_style1" id="tm_download_section">
                <div class="tm_invoice_in">
                    <div class="tm_invoice_head tm_align_center tm_mb20">
                        <div class="tm_invoice_left">
                            @php($eCommerceLogo = getWebConfig(name: 'company_web_logo'))
                            <div class="tm_logo"><img
                                    src="{{ getValidImage('storage/app/public/company/' . $eCommerceLogo, type: 'backend-logo') }}"
                                    alt="Logo"></div>
                        </div>
                        <div class="tm_invoice_right tm_text_right">
                            <div class="tm_primary_color tm_f29 tm_text_uppercase">Invoice</div>
                        </div>
                    </div>
                    <div class="tm_invoice_info tm_mb10">
                        <div class="tm_invoice_seperator tm_gray_bg"></div>
                        <div class="tm_invoice_info_list fs_2">
                            <p class="tm_invoice_number tm_m0">Invoice No: <b
                                    class="tm_primary_color">#{{ $order->invoice_id }}</b></p>
                            <p class="tm_invoice_date tm_m0">Date: <b
                                    class="tm_primary_color">{{ date('d-m-Y', strtotime($order->created_at)) }}</b></p>
                        </div>
                    </div>
                    <div class="tm_invoice_head">
                        <div class="tm_invoice_left fs_1">
                            <p class="tm_mb2"><b class="tm_primary_color">Invoice To:</b></p>
                            <p>
                                {{ getWebConfig('company_name') }} <br>
                                {{ getWebConfig('shop_address') }} <br>
                                {{ getWebConfig('company_phone') }} <br>
                                <a
                                    href="mailto:{{ getWebConfig('company_email') }}">{{ getWebConfig('company_email') }}</a>
                            </p>
                        </div>
                        @if ($order->customer)
                            <div class="tm_invoice_right tm_text_right fs_1">
                                <p class="tm_mb2"><b class="tm_primary_color">Pay To:</b></p>
                                <p>
                                    {{ $order->customer['name'] }}<br>
                                    {{ $order->customer['street_address'] }}<br>
                                    {{ $order->customer['phone'] }}<br>
                                    <a
                                        href="mailto:{{ $order->customer['email'] }}">{{ $order->customer['email'] }}</a>
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="tm_table tm_style1 tm_mb30">
                        <div class="tm_round_border">
                            <div class="tm_table_responsive fs_2">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="tm_width_3 tm_semi_bold tm_primary_color tm_gray_bg">Item</th>
                                            <th class="tm_width_3 tm_semi_bold tm_primary_color tm_gray_bg">Variant</th>
                                            <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Qty</th>
                                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Price</th>
                                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Discount
                                            </th>
                                            <th
                                                class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($sub_total = 0)
                                        @php($total_product_price = 0)
                                        @foreach ($order->details as $key => $detail)
                                            <?php
                                            $variation = json_decode($detail->variation, true);
                                            ?>
                                            <tr class="td_m1">
                                                <td class="tm_width_6"> {{ $key + 1 }}.
                                                    {{ Str::limit($detail->product['name'], 200) }}
                                                </td>
                                                <td class="tm_width_2">
                                                    @if (!empty($variation['color']))
                                                        Color: {{ $variation['color'] }} <br>
                                                    @endif
                                                    @if (!empty($variation['Size']))
                                                        Size: {{ $variation['Size'] }}
                                                    @endif
                                                </td>
                                                <td class="tm_width_1">{{ $detail['qty'] }}</td>
                                                <td class="tm_width_2">&#2547; {{ $detail['price'] }}</td>
                                                <td class="tm_width_2">&#2547; {{ $detail['discount'] }}</td>
                                                <td class="tm_width_2 tm_text_right">&#2547;
                                                    {{ $detail['price'] * $detail['qty'] - $detail['discount'] }}
                                                </td>
                                            </tr>
                                            @php($sub_total += $detail['price'] * $detail['qty'] - $detail['discount'])
                                            @php($total_product_price += $detail['price'] * $detail['qty'])
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="tm_invoice_footer">
                            <div class="tm_left_footer fs_1">
                                <div class="mb_1">
                                    <p class="tm_mb2"><b class="tm_primary_color">Return/Replacement Policy:</b></p>
                                    <ul class="m_none">
                                        <li>The replacement request must be raised within 24 hours after getting the
                                            delivery.</li>
                                        <li>Requirement For A Valid Return/Replacement must have Proof of purchase
                                            (Order number, invoice,
                                            etc).</li>
                                    </ul>
                                </div>
                                <div class="mb_1">
                                    <p class="tm_mb2"><b class="tm_primary_color">Valid Conditions And Reasons To
                                            Return/Replacement An Item (General):</b></p>
                                    <ul class="m_none">
                                        <li>Delivery of wrong product (Return).</li>
                                        <li>Delivery of Defective product.</li>
                                        <li>Delivery of the products with missing parts.</li>
                                        <li>Incorrect content on the website (Return).</li>
                                    </ul>
                                </div>
                                <div class="mb_1">
                                    <p class="tm_mb2"><b class="tm_primary_color">Note:</b></p>
                                    <ul class="m_none">
                                        <li>If you find any item missing, or if anything is damaged, please immediatly
                                            return the package
                                            to your delivery agent. After you accept the package, We are unable to
                                            receive claims, wrong or
                                            defective items.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tm_right_footer fs_1">
                                <?php

                                if ($order['extra_discount_type'] == 'percent') {
                                    $ext_discount = ($total_product_price / 100) * $order['extra_discount'];
                                } else {
                                    $ext_discount = $order['extra_discount'];
                                }
                                ?>
                                <table>
                                    <tbody>
                                        <tr class="td_m1">
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Subtoal</td>
                                            <td
                                                class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold">
                                                &#2547; {{ $sub_total }}</td>
                                        </tr>
                                        <tr class="td_m1">
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Extra
                                                Discount</td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">
                                                -&#2547; {{ $ext_discount }}</td>
                                        </tr>
                                        @if ($order['discount_amount'] > 0)
                                            <tr class="td_m1">
                                                <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Coupon
                                                    Discount
                                                </td>
                                                <td
                                                    class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">
                                                    -&#2547; {{ $order['discount_amount'] }}</td>
                                            </tr>
                                        @endif
                                        <tr class="td_m1">
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Shipping Cost
                                            </td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">
                                                +&#2547; {{ $order['shipping_cost'] }}</td>
                                        </tr>
                                        <tr class="tm_border_top tm_border_bottom td_m1">
                                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color fs_1">
                                                Grand Total
                                            </td>
                                            <td
                                                class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right fs_1">
                                                &#2547; {{ $order['order_amount'] }}
                                            </td>
                                        </tr>

                                        @if ($order['advanced'] == '1')
                                            <tr class="tm_border_top tm_border_bottom td_m1">
                                                <td
                                                    class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color fs_1">
                                                    Advanced Payment
                                                </td>
                                                <td
                                                    class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right fs_1">
                                                    &#2547; {{ $order['order_amount'] }}
                                                </td>
                                            </tr>
                                            <tr class="tm_border_top tm_border_bottom td_m1">
                                                <td
                                                    class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color fs_1">
                                                    Total Due
                                                </td>
                                                <td
                                                    class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right fs_1">
                                                    &#2547; 0
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tm_padd_15_20 tm_round_border w_45" id="invoice_tocken">
                        @php($eCommerceLogo = getWebConfig(name: 'company_web_logo'))
                        <div class="tm_logo text_center mb_2"><img
                                src="{{ getValidImage('storage/app/public/company/' . $eCommerceLogo, type: 'backend-logo') }}"
                                alt="Logo" width="150"></div>
                        <div class="fs_1 mb_2 ml_10 column_2">
                            <p class="m_none">Hot line: <strong>+8809613-241300</strong></p>
                            <p class="m_none">Date: <strong>{{ date('d-m-Y', strtotime($order->created_at)) }}</strong></p>
                        </div>
                        <div class="tm_round_border">
                            <div class="fs_2">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="tm_width_1">Invoice ID</td>
                                            <td class="tm_width_2" colspan="2">#{{ $order->invoice_id }}</td>
                                        </tr>
                                        @foreach ($order->details as $detail)
                                            <?php
                                            $variation = json_decode($detail->variation, true);
                                            ?>
                                            <tr>
                                                <td class="tm_width_1">Product</td>
                                                <td class="tm_width_2" style="width:55%; padding: 0px">
                                                    {{ Str::limit($detail->product['name'], 20) }} <br> ( @if (!empty($variation['color']))
                                                        {{ $variation['color'] }}
                                                    @endif/
                                                    @if (!empty($variation['Size']))
                                                        {{ $variation['Size'] }}
                                                    @endif)
                                                </td>
                                                <td style="width: 35%; padding: 0px">Qnt: {{ $detail->qty }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="tm_width_1">Amount</td>
                                            <td class="tm_width_2" colspan="2">&#2547;
                                                {{ $order['order_amount'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="tm_width_4 curiar_id">Courier ID</td>
                                            <td class="tm_width_4" colspan="2">
                                                <span class="curiar_id">{{ $order['third_party_delivery_consignment_id'] }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- .tm_note -->


                </div>
            </div>
            <div class="tm_invoice_btns tm_hide_print">
                <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
                    <span class="tm_btn_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewbox="0 0 512 512">
                            <path
                                d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                                fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32">
                            </path>
                            <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32"
                                fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32">
                            </rect>
                            <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none"
                                stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path>
                            <circle cx="392" cy="184" r="24" fill='currentColor'></circle>
                        </svg>
                    </span>
                    <span class="tm_btn_text">Print</span>
                </a>
                <button id="downloadPdf" class="tm_invoice_btn tm_color2">
                    <span class="tm_btn_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewbox="0 0 512 512">
                            <path
                                d="M320 336h76c55 0 100-21.21 100-75.6s-53-73.47-96-75.6C391.11 99.74 329 48 256 48c-69 0-113.44 45.79-128 91.2-60 5.7-112 35.88-112 98.4S70 336 136 336h56M192 400.1l64 63.9 64-63.9M256 224v224.03"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="32">
                            </path>
                        </svg>
                    </span>
                    <span class="tm_btn_text">Download</span>
                </button>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.getElementById("downloadPdf").addEventListener("click", function() {
            var tokenDiv = document.getElementById("invoice_tocken");

            // 🔄 w_40 class convert to w_50
            if (tokenDiv.classList.contains("w_40")) {
                tokenDiv.classList.remove("w_40");
                tokenDiv.classList.add("w_50");
            }

            // 🧾 PDF generate করো
            var element = document.getElementById("invoiceArea");
            var opt = {
                margin: 0.5,
                filename: '{{ $order->id }}_invoice.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };
            html2pdf().set(opt).from(element).save();
        });
    </script>
</body>

</html>
