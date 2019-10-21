@extends('templates.form')

@section('form')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">{{ !isset($user) ? _t('Create') : _t('Edit') }}</h3>
                </div>
            </div>
            <form class="kt-form">
                <div class="kt-portlet__body">
                    <div class="form-group">
                        <label for="name">
                            {{ _t('Name') }} <small class="d-inline-block text-secondary">{{_t('optional')}}</small>
                        </label>
                        <input class="form-control" type="text" name="name" id="name" placeholder="{{ _t('Name') }}" value="{{ isset($user->name) ? $user->name : ''}}">
                    </div>

                    <div class="form-group">
                        <label for="username">{{ _t('Username') }}</label>
                        <input class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" type="text" name="username" id="username" placeholder="{{ _t('Username') }}" value="{{ isset($user->username) ? $user->username : ''}}">
                    </div>

                    <div class="form-group">
                        <label for="email">{{ _t('Email') }}</label>
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" placeholder="{{ _t('Email') }}" value="{{ isset($user->email) ? $user->email : ''}}">
                    </div>

                    <div class="form-group">
                        <label for="mobile">{{ _t('Mobile') }}</label>
                        <input class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" type="tel" name="mobile" id="mobile" placeholder="{{ _t('Mobile') }}" value="{{ isset($user->mobile) ? $user->mobile : ''}}">
                    </div>

                    <div class="form-group">
                        <label for="password">{{ _t('password') }}</label>
                        <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" placeholder="{{ _t('password') }}">
                    </div>

                    @if (Guardio::has('users.change.status'))
                        <div class="form-group">
                            <label>{{ _t('account.status') }}</label>
                            @foreach (config('guardio.status', ['awaiting', 'active', 'disable']) as $type => $value)
                            <div class="kt-radio-list">
                                @isset ($user)
                                    <label class="kt-radio">
                                        <input type="radio" value="{{ $value }}" id="{{ $value }}" {{ $user->status == $value ? 'checked="checked"' : '' }} name="status">{{ _t("user.status.$value") }}
                                        <span></span>
                                    </label>
                                @else
                                    <label class="kt-radio">
                                        <input type="radio" value="{{ $value }}" id="{{ $value }}" {{ $value == 'waiting' ? 'checked="checked"' : '' }} name="status">{{ _t("user.status.$value") }}
                                        <span></span>
                                    </label>
                                @endisset
                            </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-group">
                        <label>
                            {{ _t('gender') }} <small class="d-inline-block text-secondary">{{_t('optional')}}</small>
                        </label>
                        <div class="kt-radio-list">
                            <label class="kt-radio">
                                <input type="radio" value="female" id="female" name="gender" {{ isset($user->gender) && $user->gender == 'female' ? 'checked="checked"' : '' }}>{{ _t('female') }}
                                <span></span>
                            </label>
                            <label class="kt-radio">
                                <input type="radio" value="male" id="male" name="gender" {{ isset($user->gender) && $user->gender == 'male' ? 'checked="checked"' : '' }}>{{ _t('male') }}
                                <span></span>
                            </label>
                        </div>
                    </div>

                    @if (Guardio::has('users.change.type'))
                        <div class="form-group">
                            <label for="type">{{ _t('user.type') }}</label>
                            <select class="form-control kt-selectpicker" name="type" id="type">
                                @foreach (config('guardio.type', ['admin', 'user']) as $type => $value)
                                    @isset ($user)
                                        <option value="{{ $value }}" {{ $user->type == $value ? 'selected="selected"' : '' }}>{{ _t("user.type.$value") }}</option>
                                    @else
                                        <option value="{{ $value }}" {{ $value == 'user' ? 'selected="selected"' : '' }}>{{ _t("user.type.$value") }}</option>
                                    @endisset
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if (Guardio::has('users.change.groups'))
                        <div class="form-group mb-0">
                            <label for="groups">{{ _t('user.groups') }}</label>
                            <select class="form-control kt-selectpicker" name="groups[]" id="groups[]" multiple>
                                @foreach ($groups ?? [] as $key => $value)
                                    @isset ($user)
                                        <option value="{{ $value }}" {{ in_array($value, $user->groups) ? 'selected="selected"' : '' }}>{{ _t("user.groups.$value") }}</option>
                                    @else
                                        <option value="{{ $value }}" {{ $value == 'user' ? 'selected="selected"' : '' }}>{{ _t("user.groups.$value") }}</option>
                                    @endisset
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button class="btn btn-primary">{{ _t('Submit') }}</button>
                        <a href="#" class="btn btn-secondary">{{ _t('Cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
