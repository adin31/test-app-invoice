@push('js')
<script type="text/javascript">
	$(document).ready(function(){

		$(document).on('click','#item-add',function() {
            var item_id = $('#item_id').val();
            var item_text = $('#item_id').html();
            var quantity = $('#quantity').val();

            if (item_id == '') {
                Swal.fire({
                    icon: 'error', 
                    html: 'Please select an Item',
                });
            } else {
                if (quantity == '' || quantity < 0) {
                    Swal.fire({
                        icon: 'error', 
                        html: 'Please set a Quantity',
                    });
                } else {                    
                    Swal.fire({
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            swal.showLoading();
                        },
                    });
                    $.ajax({
                        type: 'post',
                        url: '{{ URL::to("invoice/fetch_item") }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            item_id: item_id,
                            quantity: quantity,
                        },
                        success:function(response) {
                            var html    = '';
					        var table   = $('#item-list');
                            var row_no  = table.find('tbody> tr:last').index() + 2;
                            var amt_pay = $('#amount_paid').val();
                            var amt_now = $('#amount_total').val();
                            var amt_new = parseInt(amt_now) + parseInt(response.price_raw);
                            var amt_tax = amt_new * 0.1;
                            var amt_due = (amt_new + amt_tax) - parseInt(amt_pay);
                            html += '<tr id="row-'+row_no+'" raw-price="'+response.price_raw+'">';
                            html += '<td><a class="btn btn-sm bg-danger text-white item-remove"><i class="fa fa-times" /></a></td>';
                            html += '<td>'+response.type+'</td>';
                            html += '<td>'+response.description+'</td>';
                            html += '<td class="text-end">'+response.quantity+'</td>';
                            html += '<td class="text-end">'+response.unit_price+'</td>';
                            html += '<td class="text-end">'+response.price+'</td>';
                            html += '<input type="hidden" name="item_qty['+response.id+']" value="'+response.quantity+'">';
                            html += '</tr>';
                            table.find('tbody').append(html);

                            $('#amount_total').val(amt_new);
                            $('#amount_tax').val(amt_tax);
                            $('#amount_due').val(amt_due);

                            $('#item_id').val('');
                            $('#quantity').val('');

                            $('#item_list-error').hide();
                            $('#item_list-error').html('');

                            Swal.close();
                        },
                        error:function(){
                            //
                        }
                    });
                }
            }
		});

        $(document).on('click', '.item-remove', function (e) {			
			e.preventDefault();
			var rowCurrent	= $(this).closest('tr');

            var amt_raw = rowCurrent.attr('raw-price');
            var amt_pay = $('#amount_paid').val();
            var amt_now = $('#amount_total').val();
            var amt_new = parseInt(amt_now) - parseInt(amt_raw);
            var amt_tax = amt_new * 0.1;
            var amt_due = (amt_new + amt_tax) - parseInt(amt_pay);
            $('#amount_total').val(amt_new);
            $('#amount_tax').val(amt_tax);
            $('#amount_due').val(amt_due);

            rowCurrent.remove();
		});
        $(document).on('keyup', '#amount_paid', function (e) {			
            var amt_due = $('#amount_due').val();
			var amt_pay = $(this).val();
            var amt_new = parseInt(amt_due) - parseInt(amt_pay);
            if (amt_new < 0) {
                amt_new = 0;
            }
            $('#amount_due').val(amt_new);
		});

        $(document).on('click', '.btn-submit', function (e) {			
			e.preventDefault();
			var invoice_from_id	= $('#invoice_from_id').val();
			var invoice_for_id	= $('#invoice_for_id').val();
			var due_date        = $('#due_date').val();
			var subject         = $('#subject').val();
            var check           = true;
            var row_no          = $('#item-list').find('tbody> tr:last').index() + 2;

            if (invoice_from_id == '') {
                check = false;
                $('#invoice_from_id').addClass('is-invalid');
                $('#invoice_from_id-error').html('Please select a value.');
            }
            if (invoice_for_id == '') {
                check = false;
                $('#invoice_for_id').addClass('is-invalid');
                $('#invoice_for_id-error').html('Please select a value.');
            }
            if (due_date == '') {
                check = false;
                $('#due_date').addClass('is-invalid');
                $('#due_date-error').html('Please select a date.');
            }
            if (subject == '') {
                check = false;
                $('#subject').addClass('is-invalid');
                $('#subject-error').html('Please input value.');
            }
            if (row_no == 1) {
                check = false;
                $('#item_list-error').html('Please add item.');
            }
            if (check == false) {
                $('.invalid-feedback').show();
            } else {
                $("#form-input").submit();
            }
		});
        
		$('.form-control').keyup(function() {
			var id = $(this).attr('id');
			$('#'+id).removeClass('is-invalid');
			$('#'+id+'-error').hide();
			$('#'+id+'-error').html('');
		});
		$('.form-control').change(function() {
			var id = $(this).attr('id');
			$('#'+id).removeClass('is-invalid');
			$('#'+id+'-error').hide();
			$('#'+id+'-error').html('');
		});

	});
</script>
@endpush