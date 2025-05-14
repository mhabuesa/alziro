"use strict";

$(document).ready(function() {
    try {
        initAutocomplete();
    } catch (error) {}
    try {
        billingMap();
    } catch (error) {}

    if($('input[name="shipping_method_id"]').is(':checked')){
        let cardBody = $('[name="shipping_method_id"]:checked').parents('.address-card').find('.address-card-body')
        shipping_method_select(cardBody);
    }
    if($('input[name="billing_method_id"]').is(':checked')){
        let cardBody = $('[name="billing_method_id"]:checked').parents('.address-card').find('.address-card-body')
        billing_method_select(cardBody);
    }

});

$('[name="shipping_method_id"]').on('change', function(){
    let cardBody = $(this).parents('.address-card').find('.address-card-body')
    shipping_method_select(cardBody);
})


$('[name="billing_method_id"]').on('change', function(){
    let cardBody = $(this).parents('.address-card').find('.address-card-body')
    billing_method_select(cardBody);
})

$('#same_as_shipping_address').on('click', function(){
    let check_same_as_shippping = $('#same_as_shipping_address').is(":checked");
    if (check_same_as_shippping) {
        $('#hide_billing_address').slideUp();
    } else {
        $('#hide_billing_address').slideDown();
    }
})


function shipping_method_select(cardBody){
    let update_this_address = $('.text-custom-storage').data('update-this-address');
    let shipping_method_id = $('[name="shipping_method_id"]:checked').val();
    let shipping_person = cardBody.find('.shipping-contact-person').text();
    let shipping_phone = cardBody.find('.shipping-contact-phone').text();
    let shipping_address = cardBody.find('.shipping-contact-address').text();
    let shipping_city = cardBody.find('.shipping-contact-city').text();
    let shipping_zip = cardBody.find('.shipping-contact-zip').text();
    let shipping_country = cardBody.find('.shipping-contact-country').text();
    let shipping_contact_address_type = cardBody.find('.shipping-contact-address_type').text();
    let update_address = `
        <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="${shipping_method_id}">
        <input type="checkbox" name="update_address" id="update_address" class="form-check-input dark-form-check-input"> ${update_this_address}`;

    $('#name').val(shipping_person);
    $('#phone_number').val(shipping_phone);
    $('#address').val(shipping_address);
    $('#city').val(shipping_city);
    $('#zip').val(shipping_zip);
    $('#select2-zip-container').text(shipping_zip);
    $('#country').val(shipping_country);
    $('#select2-country-container').text(shipping_country);
    $('#address_type').val(shipping_contact_address_type);
    $('#save_address_label').html(update_address);
}

function billing_method_select(cardBody){
    let update_this_address = $('.text-custom-storage').data('update-this-address');
    let billing_method_id = $('[name="billing_method_id"]:checked').val();
    let billing_person = cardBody.find('.billing-contact-name').text();
    let billing_phone = cardBody.find('.billing-contact-phone').text();
    let billing_address = cardBody.find('.billing-contact-address').text();
    let billing_city = cardBody.find('.billing-contact-city').text();
    let billing_zip = cardBody.find('.billing-contact-zip').text();
    let billing_country = cardBody.find('.billing-contact-country').text();
    let billing_contact_address_type = cardBody.find('.billing-contact-address_type').text();
    let update_address_billing = `
        <input type="hidden" name="billing_method_id" id="billing_method_id" value="${billing_method_id}">
        <input type="checkbox" name="update_billing_address" id="update_billing_address" class="form-check-input dark-form-check-input"> ${update_this_address}`;

    $('#billing_contact_person_name').val(billing_person);
    $('#billing_phone').val(billing_phone);
    $('#billing_address').val(billing_address);
    $('#billing_city').val(billing_city);
    $('#billing_zip').val(billing_zip);
    $('#select2-billing_zip-container').text(billing_zip);
    $('#billing_country').val(billing_country);
    $('#select2-billing_country-container').text(billing_country);
    $('#billing_address_type').val(billing_contact_address_type);
    $('#save-billing-address-label').html(update_address_billing);
}

function initAutocomplete() {
    let myLatLng = {
        lat: $('#shippingaddress-storage').data('latitude'),
        lng: $('#shippingaddress-storage').data('longitude'),
    };

    const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
        center: {
            lat: $('#shippingaddress-storage').data('latitude'),
            lng: $('#shippingaddress-storage').data('longitude'),
        },
        zoom: 13,
        mapTypeId: "roadmap",
    });

    let marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
    });

    marker.setMap( map );
    var geocoder = geocoder = new google.maps.Geocoder();
    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
        var coordinate = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
        var coordinates = JSON.parse(coordinate);
        var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
        marker.setPosition( latlng );
        map.panTo( latlng );

        document.getElementById('latitude').value = coordinates['lat'];
        document.getElementById('longitude').value = coordinates['lng'];

        geocoder.geocode({ 'latLng': latlng }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    document.getElementById('address').value = results[1].formatted_address;
                    console.log(results[1].formatted_address);
                }
            }
        });
    });

    const input = document.getElementById("pac-input");

    const searchBox = new google.maps.places.SearchBox(input);

    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
    map.addListener("bounds_changed", () => {
        searchBox.setBounds(map.getBounds());
    });
    let markers = [];
    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }
        markers.forEach((marker) => {
            marker.setMap(null);
        });
        markers = [];

        const bounds = new google.maps.LatLngBounds();
        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
                console.log("Returned place contains no geometry");
                return;
            }
            var mrkr = new google.maps.Marker({
                map,
                title: place.name,
                position: place.geometry.location,
            });

            google.maps.event.addListener(mrkr, "click", function (event) {
                document.getElementById('latitude').value = this.position.lat();
                document.getElementById('longitude').value = this.position.lng();

            });

            markers.push(mrkr);

            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
}

function billingMap() {
    let myLatLng = {
        lat: $('#shippingaddress-storage').data('latitude'),
        lng: $('#shippingaddress-storage').data('longitude'),
     };
    const map = new google.maps.Map(document.getElementById("billing_location_map_canvas"), {
        center: {
            lat: $('#shippingaddress-storage').data('latitude'),
            lng: $('#shippingaddress-storage').data('longitude'),
        },
        zoom: 13,
        mapTypeId: "roadmap",
    });

    let marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
    });

    marker.setMap( map );
    var geocoder = geocoder = new google.maps.Geocoder();
    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
        var coordinate = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
        var coordinates = JSON.parse(coordinate);
        var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
        marker.setPosition( latlng );
        map.panTo( latlng );

        document.getElementById('billing_latitude').value = coordinates['lat'];
        document.getElementById('billing_longitude').value = coordinates['lng'];

        geocoder.geocode({ 'latLng': latlng }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    document.getElementById('billing_address').value = results[1].formatted_address;
                    console.log(results[1].formatted_address);
                }
            }
        });
    });

    const input = document.getElementById("pac-input-billing");

    const searchBox = new google.maps.places.SearchBox(input);

    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
    map.addListener("bounds_changed", () => {
        searchBox.setBounds(map.getBounds());
    });
    let markers = [];
    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }
        markers.forEach((marker) => {
            marker.setMap(null);
        });
        markers = [];

        const bounds = new google.maps.LatLngBounds();
        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
                console.log("Returned place contains no geometry");
                return;
            }
            var mrkr = new google.maps.Marker({
                map,
                title: place.name,
                position: place.geometry.location,
            });

            google.maps.event.addListener(mrkr, "click", function (event) {
                document.getElementById('billing_latitude').value = this.position.lat();
                document.getElementById('billing_longitude').value = this.position.lng();

            });

            markers.push(mrkr);

            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
};

$(document).on("keydown", "input", function(e) {
    if (e.which==13) e.preventDefault();
});

function mapsShopping() {
    try {
        initAutocomplete();
    } catch (error) {}
    try {
        billingMap();
    } catch (error) {}
}

$('#proceed_to_next_action').on('click', function(){
    let physical_product = $('#physical_product').val();

    if(physical_product === 'yes') {
        var billing_addresss_same_shipping = $('#same_as_shipping_address').is(":checked");

        let allAreFilled = true;
        document.getElementById("address-form").querySelectorAll("[required]").forEach(function (i) {
            if (!allAreFilled) return;
            if (!i.value) allAreFilled = false;
            if (i.type === "radio") {
                let radioValueCheck = false;
                document.getElementById("address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                    if (r.checked) radioValueCheck = true;
                });
                allAreFilled = radioValueCheck;
            }
        });

        let allAreFilled_shipping = true;

        if (billing_addresss_same_shipping != true && $('#billing_input_enable').val() == 1) {

            document.getElementById("billing-address-form").querySelectorAll("[required]").forEach(function (i) {
                if (!allAreFilled_shipping) return;
                if (!i.value) allAreFilled_shipping = false;
                if (i.type === "radio") {
                    let radioValueCheck = false;
                    document.getElementById("billing-address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                        if (r.checked) radioValueCheck = true;
                    });
                    allAreFilled_shipping = radioValueCheck;
                }
            });
        }
    }else {
        var billing_addresss_same_shipping = false;
    }

    let redirect_url = $(this).data('checkoutpayment');
    let form_url = $(this).data('gotocheckout');

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.post({
        url: form_url,
        data: {
            physical_product: physical_product,
            shipping: physical_product === 'yes' ? $('#address-form').serialize() : null,
            billing: $('#billing-address-form').serialize(),
            billing_addresss_same_shipping: billing_addresss_same_shipping
        },

        beforeSend: function () {
            $('#loading').addClass('d-grid');
        },
        success: function (data) {
            console.log(data)
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                location.href = redirect_url;
            }
        },
        complete: function () {
            $('#loading').removeClass('d-grid');
        },
        error: function (data) {
            let error_msg = data.responseJSON.errors;
            toastr.error(error_msg, {
                CloseButton: true,
                ProgressBar: true
            });
        }
    });
});
