(function($) {
    $(function() {
        var $donationEl = $('.donation-sc');
        var minDonation = 1;
        var maxDonation = $donationEl.find('input[name="max_donation"]').val() * 1;
        var currentRaisedAmt = $donationEl.find('input[name="raised_amount"]').val() * 1;
        var targetAmt = $donationEl.find('input[name="target_amount"]').val() * 1;
        
        var isEmailValid = function(email) {
            return email.match(
                /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
            );
        };
        
        var isPhoneValid = function(phone) {
            return phone.match(
                /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/
            );
        };
        
        $('#donate-button').on('click', function() {
            var getValueByName = function(name, psuedoClass) {
                psuedoClass = psuedoClass || '';
                
                return $donationEl.find('input[name="' + name + '"]' + psuedoClass).val().trim();
            };
            
            var fname = getValueByName('first_name');
            var lname = getValueByName('last_name');
            var email = getValueByName('email');
            var phone = getValueByName('phone');
            var paymentMethod = getValueByName('payment_method', ':checked');
            var amount = getValueByName('amount') * 1; // cast to a number
            var errorMessage = '';
            
            var $button = $(this);
            var buttonLabel = $(this).html();
            
            if (! fname 
                || ! lname 
                || ! email 
                || ! phone 
                || ! paymentMethod
                || ! amount
            ) {
                errorMessage = '<p>Please enter all required fields.</p>';
            } else if (! isEmailValid(email)) {
                errorMessage = '<p>Please enter a valid email address.</p>';
            } else if (! isPhoneValid(phone)) {
                errorMessage = '<p>Please enter a valid phone number.</p>';
                errorMessage += '<p>Formats may be of the ff: </p>';
                errorMessage += '<ul class="list-group">';
                errorMessage += '<li>XX XXXX XXXX</li>';
                errorMessage += '<li>XX-XXXX-XXXX</li>';
                errorMessage += '<li>XX.XXXX.XXXX</li>';
                errorMessage += '</ul>';
            } else if (amount > maxDonation) {
                errorMessage = '<p>Max donation is ' + maxDonation + '.</p>';
            } else if (amount < minDonation) {
                errorMessage = '<p>Min donation is ' + minDonation + '.</p>';
            }
            
            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Input Invalid',
                    html: errorMessage
                });
                return; 
            }
            
            if ($button.hasClass('disabled')) {
                return;
            }
            
            $.ajax({
                url: donations.ajaxurl, 
                method: 'post',
                data: {
                    first_name: fname, 
                    last_name: lname, 
                    email: email, 
                    phone: phone,
                    payment_method: paymentMethod, 
                    amount_donated: amount,
                    action: 'donate'
                },
                beforeSend: function() {
                    $button.html('Submitting...');
                    $button.addClass('disabled');
                }
            }).done(function(response) {
                if (response) {
                    if (response.data.raised_amount) {
                        $donationEl.find('input[name="raised_amount"]').val(response.data.raised_amount);
                        $donationEl.trigger('raisedAmountChanged', [response.data.raised_amount]);
                    } 
                    if (response.data.raised_amount_formatted) {
                        $donationEl.find('.raised-amount span').html(response.data.raised_amount_formatted);
                    }
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'Donation',
                    html: 'Thank you for your donation!'
                });
                
                // resets form submission
                $donationEl.find(':input:not([type=hidden]):not([type=radio])').val('');
                $donationEl.find('input[name="payment_method"]').filter('[value="paypal"]').prop('checked', true);
                
            }).fail(function(error) {
                errorMessage = error.responseJSON ? error.responseJSON.message : 'Error encountered while submitting form.';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Form Submission Error',
                    html: errorMessage
                });
            }).always(function() {
                $button.html(buttonLabel);
                $button.removeClass('disabled');
            })
        });
        
        $('input[name="amount"]', $donationEl).on('change', function() {
            var $this = $(this);
            var amount = $this.val() * 1;
            var raisedAmt = $donationEl.find('input[name="raised_amount"]').val() * 1;
            
            amount = (amount > maxDonation) ? maxDonation : ((amount < minDonation) ? minDonation : amount);
            
            $donationEl.find('input[name="amount"]').each(function(key, element) {
                $(element).val(amount);
            });
            
            raisedAmt += amount;
            
            $donationEl.trigger('raisedAmountChanged', [raisedAmt]);
        });
        
        $donationEl.on('raisedAmountChanged', function(event, newRaisedAmount) {
            newRaisedAmount *= 1; // casting to a number
            newRaisedAmount = newRaisedAmount || 0;
            $donationEl.find('.progress-bar').css('width', (newRaisedAmount / targetAmt * 100) + '%');
        });
    });
})(jQuery);
