<div class="container donation-sc">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="form-container py-5 px-5">
                <form class="mt-5 px-5" method="post">
                    <?php if (isset($args['title'])): ?>
                        <h3><?php echo esc_html($args['title']); ?></h3>
                    <?php endif; ?>
                    
                    <p class="raised-amount text-center">&#36;<span><?php echo esc_html(number_format($args['raised_amount'])); ?></span></p>
                    <p class="target-amount text-center">of &#36;<?php echo esc_html($args['formatted_target_amount']); ?> raised</p>
                    
                    <div class="d-flex justify-content-center mb-5">
                        <div class="progress w-75">
                          <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo esc_attr($args['raised_percent']); ?>" aria-valuemin="0" aria-valuemax="100"
                                style="width: <?php echo esc_attr($args['raised_percent']); ?>%;"></div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        <div class="input-group w-25">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control donation-total"  name="amount" min="1" max="<?php echo esc_attr($args['max_donation']); ?>" step="1">
                        </div>
                    </div>
                    
                    <?php if ($args['content']): ?>
                        <p class="w-75 mt-3 ms-auto me-auto donation-sc-content"><?php echo $args['content']; ?></p>
                    <?php endif; ?>
                
                    <div class="row">
                        <h4 class="form-title">Select payment method</h4>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input mt-2" type="radio" name="payment_method" checked value="paypal" id="method_paypal">
                                <label class="form-check-label" for="method_paypal">
                                    Paypal
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input mt-2" type="radio" name="payment_method" value="offline" id="method_offline">
                                <label class="form-check-label" for="method_offline">
                                    Offline donation
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h4 class="form-title">Personal Info</h4>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="First name*" aria-label="First name" name="first_name">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Last name*" aria-label="Last name" name="last_name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Email*" aria-label="Email" name="email">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Phone*" aria-label="Phone" name="phone">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 offset-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text donation-text-label">Donation total:</span>
                                <input type="number" class="form-control donation-total" name="amount" min="1" max="<?php echo esc_attr($args['max_donation']); ?>" step="1">
                            </div>
                            
                            <p class="text-center">
                                <button type="button" id="donate-button" class="btn btn-primary text-uppercase w-75">donate now</button>
                            </p>
                        </div>
                    </div>
                    <input type="hidden" name="max_donation" value="<?php echo esc_attr($args['max_donation']); ?>">
                    <input type="hidden" name="raised_amount" value="<?php echo esc_attr($args['raised_amount']); ?>">
                    <input type="hidden" name="target_amount" value="<?php echo esc_attr($args['target_amount']); ?>">
                </form>
            </div><!-- /.form-container -->
        </div>
    </div>
</div>