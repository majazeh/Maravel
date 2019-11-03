<div class="form-group">
    <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar">
        <div class="kt-avatar__holder" style="background-image: url({{ isset($user) && $user->avatar ? $user->avatar['250x']->url : asset('assets/media/users/default.jpg') }})"></div>
        <label class="kt-avatar__upload" data-toggle="kt-tooltip" data-original-title="{{ _t('Change avatar') }}" title>
            <i class="fa fa-pen"></i>
            <input type="file" name="avatar" accept=".png, .jpg, .jpeg, .gif">
        </label>
        <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
            <i class="fa fa-times"></i>
        </span>
    </div>
</div>
