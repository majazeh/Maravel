<div class="kt-header__topbar-item dropdown">
    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
        <span class="kt-header__topbar-icon">
            <img src="{{ asset('assets/media/icons/svg/Shopping/Cart%233.svg') }}" alt="">
        </span>
    </div>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
        <form>
            <!-- begin:: Mycart -->
            <div class="kt-mycart">
                <div class="kt-mycart__head kt-head" style="background-image: url({{ asset('assets/media/misc/bg-1.jpg') }});">
                    <div class="kt-mycart__info">
                        <span class="kt-mycart__icon">
                            <i class="flaticon2-shopping-cart-1 kt-font-success"></i>
                        </span>
                        <h3 class="kt-mycart__title">My Cart</h3>
                    </div>
                    <div class="kt-mycart__button">
                        <button type="button" class="btn btn-success btn-sm" style=" ">2 Items</button>
                    </div>
                </div>
                <div class="kt-mycart__body kt-scroll" data-scroll="true" data-height="245" data-mobile-height="200">
                    <div class="kt-mycart__item">
                        <div class="kt-mycart__container">
                            <div class="kt-mycart__info">
                                <a href="#" class="kt-mycart__title">Samsung</a>
                                <span class="kt-mycart__desc">Profile info, Timeline etc</span>
                                <div class="kt-mycart__action">
                                    <span class="kt-mycart__price">$ 450</span>
                                    <span class="kt-mycart__text">for</span>
                                    <span class="kt-mycart__quantity">7</span>
                                    <a href="#" class="btn btn-label-success btn-icon">&minus;</a>
                                    <a href="#" class="btn btn-label-success btn-icon">&plus;</a>
                                </div>
                            </div>
                            <a href="#" class="kt-mycart__pic">
                                <img src="{{ asset('assets/media/products/product9.jpg') }}" title="">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="kt-mycart__footer">
                    <div class="kt-mycart__section">
                        <div class="kt-mycart__subtitel">
                            <span>Sub Total</span>
                            <span>Taxes</span>
                            <span>Total</span>
                        </div>
                        <div class="kt-mycart__prices">
                            <span>$ 840.00</span>
                            <span>$ 72.00</span>
                            <span class="kt-font-brand">$ 912.00</span>
                        </div>
                    </div>
                    <div class="kt-mycart__button kt-align-right">
                        <button type="button" class="btn btn-primary btn-sm">Place Order</button>
                    </div>
                </div>
            </div>

            <!-- end:: Mycart -->
        </form>
    </div>
</div>