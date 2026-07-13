<script>
(function($) {
  "use strict";
   validate_debit_note_form();
       // Init accountacy currency symbol
    init_db_currency();

    <?php if(get_purchase_option('item_by_vendor') != 1){ ?>
      init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'purchase/pur_commodity_code_search');
    <?php } ?>

    $("body").on('change', 'select[name="item_select"]', function () {
      var itemid = $(this).selectpicker('val');
      if (itemid != '') {
        pur_add_item_to_preview(itemid);
      }
    });

    $('select[name="vendorid"]').on('change', function(){
    	var vendor = $(this).val();
    	if(vendor != '' && vendor != null && vendor != undefined){
	    	requestGetJSON('purchase/vendor_change_data/' + vendor ).done(function (response) {

	            for (var f in billingAndShippingFields) {
	                if (billingAndShippingFields[f].indexOf('billing') > -1) {
	                    if (billingAndShippingFields[f].indexOf('country') > -1) {
	                        $('select[name="' + billingAndShippingFields[f] + '"]').selectpicker('val', response['billing_shipping'][0][billingAndShippingFields[f]]);
	                    } else {
	                        if (billingAndShippingFields[f].indexOf('billing_street') > -1) {
	                            $('textarea[name="' + billingAndShippingFields[f] + '"]').val(response['billing_shipping'][0][billingAndShippingFields[f]]);
	                        } else {
	                            $('input[name="' + billingAndShippingFields[f] + '"]').val(response['billing_shipping'][0][billingAndShippingFields[f]]);
	                        }
	                    }
	                }
	            }

	            if (!empty(response['billing_shipping'][0]['shipping_street'])) {
	                $('input[name="include_shipping"]').prop("checked", true).change();
	            }

	            for (var fsd in billingAndShippingFields) {
	                if (billingAndShippingFields[fsd].indexOf('shipping') > -1) {
	                    if (billingAndShippingFields[fsd].indexOf('country') > -1) {
	                        $('select[name="' + billingAndShippingFields[fsd] + '"]').selectpicker('val', response['billing_shipping'][0][billingAndShippingFields[fsd]]);
	                    } else {
	                        if (billingAndShippingFields[fsd].indexOf('shipping_street') > -1) {
	                            $('textarea[name="' + billingAndShippingFields[fsd] + '"]').val(response['billing_shipping'][0][billingAndShippingFields[fsd]]);
	                        } else {
	                            $('input[name="' + billingAndShippingFields[fsd] + '"]').val(response['billing_shipping'][0][billingAndShippingFields[fsd]]);
	                        }
	                    }
	                }
	            }

	            init_billing_and_shipping_details();

	            var vendor_currency = response['vendor_currency'];
	            var s_currency = $("body").find('.accounting-template select[name="currency"]');
	            vendor_currency = parseInt(vendor_currency);
	            vendor_currency != 0 ? s_currency.val(vendor_currency) : s_currency.val(s_currency.data('base'));
	           
	            s_currency.selectpicker('refresh');

	            <?php if(get_purchase_option('item_by_vendor') == 1){ ?>
			        if(response.option_html != ''){
			         $('#item_select').html(response.option_html);
			         $('.selectpicker').selectpicker('refresh');
			        }else if(response.option_html == ''){
			          init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'purchase/pur_commodity_code_search/purchase_price/can_be_purchased/'+invoker.value);
			        }
			        
			    <?php } ?>


	            init_currency();
	        });
	    }
    });
})(jQuery);

// Add item to preview
function pur_add_item_to_preview(id) {
  requestGetJSON("purchase/get_item_by_id/" + id).done(function (
    response
  ) {
    clear_item_preview_values();

    $('.main textarea[name="description"]').val(response.code_description);
    $('.main textarea[name="long_description"]').val(
      response.long_description.replace(/(<|&lt;)br\s*\/*(>|&gt;)/g, " ")
    );
    $('.main .item_id_display').html('ID: ' + response.itemid);

    //_set_item_preview_custom_fields_array(response.custom_fields);

    $('.main input[name="quantity"]').val(1);

    var taxSelectedArray = [];
    if (response.taxname && response.taxrate) {
      taxSelectedArray.push(response.taxname + "|" + response.taxrate);
    }
    if (response.taxname_2 && response.taxrate_2) {
      taxSelectedArray.push(response.taxname_2 + "|" + response.taxrate_2);
    }

    $(".main select.tax").selectpicker("val", taxSelectedArray);
    $('.main input[name="unit"]').val(response.unit_name || response.unit || "");
    $('.main input[name="hsn_code"]').val(response.hsn_code || response.commodity_code || "");
    $('.main input[name="discount_item"]').val(0);

    var $currency = $("body").find(
      '.accounting-template select[name="currency"]'
    );
    var baseCurency = $currency.attr("data-base");
    var selectedCurrency = $currency.find("option:selected").val();
    var $rateInputPreview = $('.main input[name="rate"]');

    if (baseCurency == selectedCurrency) {
      $rateInputPreview.val(response.purchase_price);
    } else {
      var itemCurrencyRate = response["rate_currency_" + selectedCurrency];
      if (!itemCurrencyRate || parseFloat(itemCurrencyRate) === 0) {
        $rateInputPreview.val(response.purchase_price);
      } else {
        $rateInputPreview.val(itemCurrencyRate);
      }
    }

    $(document).trigger({
      type: "item-added-to-preview",
      item: response,
      item_type: "item",
    });
  });
}

function validate_debit_note_form(selector) {
	"use strict";
    selector = typeof (selector) == 'undefined' ? '#debit-note-form' : selector;

    appValidateForm($(selector), {
        vendorid: 'required',
        date: 'required',
        currency: 'required',
        number: {
            required: true,
        }
    });

    $("body").find('input[name="number"]').rules('add', {
        remote: {
            url: admin_url + "purchase/validate_debit_note_number",
            type: 'post',
            data: {
                number: function () {
                    return $('input[name="number"]').val();
                },
                isedit: function () {
                    return $('input[name="number"]').data('isedit');
                },
                original_number: function () {
                    return $('input[name="number"]').data('original-number');
                },
                date: function () {
                    return $(".debit_note input[name='date']").val();
                },
            }
        },
        messages: {
            remote: app.lang.debit_note_number_exists,
        }
    });
}

function init_db_currency(id, callback) {
  var $accountingTemplate = $("body").find(".accounting-template");

  if ($accountingTemplate.length || id) {
    var selectedCurrencyId = !id
      ? $accountingTemplate.find('select[name="currency"]').val()
      : id;

    requestGetJSON("misc/get_currency/" + selectedCurrencyId).done(function (
      currency
    ) {
      // Used for formatting money
      accounting.settings.currency.decimal = currency.decimal_separator;
      accounting.settings.currency.thousand = currency.thousand_separator;
      accounting.settings.currency.symbol = currency.symbol;
      accounting.settings.currency.format =
        currency.placement == "after" ? "%v %s" : "%s%v";
      calculate_total();

      if (callback) {
        callback();
      }
    });
  }
}

function add_item_to_table(data, itemid, merge_invoice, bill_expense) {
  // If not custom data passed get from the preview
  data = typeof (data) == 'undefined' || data == 'undefined' ? get_item_preview_values() : data;
  if (data.description === "" && data.long_description === "" && data.rate === "") {
    return;
  }

  var table_row = '';
  var item_key = lastAddedItemKey ? lastAddedItemKey += 1 : $("body").find('tbody .item').length + 1;
  lastAddedItemKey = item_key;

  table_row += '<tr class="sortable item">';

  table_row += '<td class="dragger">';

  // Check if quantity is number
  if (isNaN(data.qty)) {
    data.qty = 1;
  }

  // Check if rate is number
  if (data.rate === '' || isNaN(data.rate)) {
    data.rate = 0;
  }

  var amount = data.rate * data.qty;
  amount = accounting.formatNumber(amount);
  var tax_name = 'newitems[' + item_key + '][taxname][]';
  $("body").append('<div class="dt-loader"></div>');
  var regex = /<br[^>]*>/gi;

  get_taxes_dropdown_template(tax_name, data.taxname).done(function (tax_dropdown) {
    // order input
    table_row += '<input type="hidden" class="order" name="newitems[' + item_key + '][order]">';
    table_row += '</td>';

    table_row += '<td><textarea name="newitems[' + item_key + '][long_description]" class="form-control" rows="5">' + data.long_description.replace(regex, "\n") + '</textarea></td>';

    table_row += '<td class="bold description"><textarea name="newitems[' + item_key + '][description]" class="form-control" rows="5">' + data.description + '</textarea><div style="font-size: 11px; color: #777;">ID: ' + (data.itemid ? data.itemid : '') + '</div></td>';

    table_row += '<td><input type="text" name="newitems[' + item_key + '][hsn_code]" class="form-control" value="' + (data.hsn_code ? data.hsn_code : '') + '"></td>';

    table_row += '<td><input type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity name="newitems[' + item_key + '][qty]" value="' + data.qty + '" class="form-control"></td>';

    table_row += '<td><input type="text" placeholder="<?php echo _l('unit'); ?>" name="newitems[' + item_key + '][unit]" class="form-control" value="' + data.unit + '"></td>';
    
    table_row += '<td><input type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" name="newitems[' + item_key + '][discount_item]" value="' + (data.discount_item ? data.discount_item : 0) + '" class="form-control"></td>';

    table_row += '<td class="rate"><input type="number" data-toggle="tooltip" title="<?php echo _l('numbers_not_formatted_while_editing'); ?>" onblur="calculate_total();" onchange="calculate_total();" name="newitems[' + item_key + '][rate]" value="' + data.rate + '" class="form-control"></td>';
    table_row += '<td class="taxrate">' + tax_dropdown + '</td>';

    var amount = data.rate * data.qty;
    if(data.discount_item > 0){
        amount = amount - (amount * data.discount_item / 100);
    }
    amount = accounting.formatNumber(amount);

    table_row += '<td class="amount" align="right">' + amount + '</td>';
    table_row += '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this,' + itemid + '); return false;"><i class="fa fa-times"></i></a></td>';
    table_row += '</tr>';

    $('table.credite-note-items-table.items tbody').append(table_row);

    $(document).trigger({
      type: 'item-added-to-table',
      data: data,
      row: table_row
    });

    setTimeout(function () {
      calculate_total();
    }, 15);

    init_selectpicker();
    clear_item_preview_values();
    $('body').find('#items-warning').remove();
    $("body").find('.dt-loader').remove();

    return true;
  });

  return false;
}

function get_item_preview_values() {
  var response = {};
  response.description = $('.main textarea[name="description"]').val();
  response.long_description = $('.main textarea[name="long_description"]').val();
  response.qty = $('.main input[name="quantity"]').val();
  response.unit = $('.main input[name="unit"]').val();
  response.hsn_code = $('.main input[name="hsn_code"]').val();
  response.taxname = $('.main select.tax').selectpicker('val');
  response.rate = $('.main input[name="rate"]').val();
  response.discount_item = $('.main input[name="discount_item"]').val();
  response.itemid = $('.main .item_id_display').text().replace('ID: ', '');

  return response;
}

function clear_item_preview_values(parent) {
  var $item_area = $(parent).find('.main');
  if (parent === undefined || parent === '') {
    $item_area = $('.main');
  }

  $item_area.find('textarea').val('');
  $item_area.find('input').val('');
  $item_area.find('input[name="quantity"]').val(1);
  $item_area.find('select.tax').selectpicker('val', '');
  $item_area.find('#item_select').selectpicker('val', '');
  $item_area.find('.item_id_display').html('');
}

function calculate_total() {
  if ($("body").hasClass("no-calculate-total")) {
    return false;
  }

  var calculated_tax,
    taxrate,
    item_taxes,
    row,
    _amount,
    _tax_name,
    taxes = {},
    taxes_rows = [],
    subtotal = 0,
    total = 0,
    quantity = 1,
    total_discount_calculated = 0,
    rows = $(".table.has-calculations tbody tr.item"),
    discount_area = $("#discount_area"),
    adjustment = $('input[name="adjustment"]').val(),
    discount_percent = $('input[name="discount_percent"]').val(),
    discount_fixed = $('input[name="discount_total"]').val(),
    discount_total_type = $(".discount-total-type.selected"),
    discount_type = $('select[name="discount_type"]').val();

  $(".tax-area").remove();

  $.each(rows, function () {
    quantity = $(this).find("[data-quantity]").val();
    if (quantity === "") {
      quantity = 1;
      $(this).find("[data-quantity]").val(1);
    }

    _amount = accounting.toFixed(
      $(this).find("td.rate input").val() * quantity,
      app.options.decimal_places
    );
    _amount = parseFloat(_amount);
    
    var discount_item = $(this).find('input[name*="[discount_item]"]').val();
    if(discount_item > 0){
        var discount_amount = (_amount * discount_item / 100);
        _amount = _amount - discount_amount;
    }

    $(this).find("td.amount").html(format_money(_amount, true));

    subtotal += _amount;
    row = $(this);
    item_taxes = $(this).find("select.tax").selectpicker("val");

    if (item_taxes) {
      $.each(item_taxes, function (i, taxname) {
        taxrate = row
          .find('select.tax [value="' + taxname + '"]')
          .data("taxrate");
        calculated_tax = (_amount / 100) * taxrate;
        if (!taxes.hasOwnProperty(taxname)) {
          if (taxrate != 0) {
            _tax_name = taxname.split("|");
            tax_row =
              '<tr class="tax-area"><td>' +
              _tax_name[0] +
              "(" +
              taxrate +
              '%)</td><td id="tax_id_' +
              slugify(taxname) +
              '"></td></tr>';
            $(discount_area).after(tax_row);
            taxes[taxname] = calculated_tax;
          }
        } else {
          // Increment total from this tax
          taxes[taxname] = taxes[taxname] += calculated_tax;
        }
      });
    }
  });

  // Discount by percent
  if (
    discount_percent !== "" &&
    discount_percent != 0 &&
    discount_type == "before_tax" &&
    discount_total_type.hasClass("discount-type-percent")
  ) {
    total_discount_calculated = (subtotal * discount_percent) / 100;
  } else if (
    discount_fixed !== "" &&
    discount_fixed != 0 &&
    discount_type == "before_tax" &&
    discount_total_type.hasClass("discount-type-fixed")
  ) {
    total_discount_calculated = discount_fixed;
  }

  $.each(taxes, function (taxname, total_tax) {
    if (
      discount_percent !== "" &&
      discount_percent != 0 &&
      discount_type == "before_tax" &&
      discount_total_type.hasClass("discount-type-percent")
    ) {
      total_tax_calculated = (total_tax * discount_percent) / 100;
      total_tax = total_tax - total_tax_calculated;
    } else if (
      discount_fixed !== "" &&
      discount_fixed != 0 &&
      discount_type == "before_tax" &&
      discount_total_type.hasClass("discount-type-fixed")
    ) {
      var t = (discount_fixed / subtotal) * 100;
      total_tax = total_tax - (total_tax * t) / 100;
    }

    total += total_tax;
    total_tax = format_money(total_tax);
    $("#tax_id_" + slugify(taxname)).html(total_tax);
  });

  total = total + subtotal;

  // Discount by percent
  if (
    discount_percent !== "" &&
    discount_percent != 0 &&
    discount_type == "after_tax" &&
    discount_total_type.hasClass("discount-type-percent")
  ) {
    total_discount_calculated = (total * discount_percent) / 100;
  } else if (
    discount_fixed !== "" &&
    discount_fixed != 0 &&
    discount_type == "after_tax" &&
    discount_total_type.hasClass("discount-type-fixed")
  ) {
    total_discount_calculated = discount_fixed;
  }

  total = total - total_discount_calculated;
  adjustment = parseFloat(adjustment);

  // Check if adjustment not empty
  if (!isNaN(adjustment)) {
    total = total + adjustment;
  }

  var discount_html = "-" + format_money(total_discount_calculated);
  $('input[name="discount_total"]').val(
    accounting.toFixed(total_discount_calculated, app.options.decimal_places)
  );

  // Append, format to html and display
  $(".discount-total").html(discount_html);
  $(".adjustment").html(format_money(adjustment));
  $(".subtotal").html(format_money(subtotal) + '<input type="hidden" name="subtotal" value="'+subtotal+'">');
  $(".total").html(format_money(total) + '<input type="hidden" name="total_amount" value="'+total+'">');

  $(document).trigger("debit-note-total-calculated");
}
</script>