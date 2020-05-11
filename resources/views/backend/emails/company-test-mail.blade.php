
@component('mail::message')

<h2>Dear<?php
	if($company){
		echo ', '.$company->company_name;
	}
	?>!</h2>

Welcome to {{ str_replace('-', ' ', config('app.name')) }} family.<br>
<br>
Thanks for registering your company.
This is to inform you that you just created your company details and your updated company details are as follows.

@component('mail::table')
| Property       	    | Value         		            |
| ---------------------     |:-------------------------------------:|
| Company Name		    | {{$company->company_name}}    |
| Logo                      | <img style="vertical-align: middle; max-height: 40px;" src="{{asset($company->getFirstMediaUrl('logo'))}}" alt=""> |
| Phone		            | {{$company->phone}}           |
| City		            | {{$company->city}}            |
| Zip Code		    | {{$company->zip_code}}        |
| Country		    | {{$company->country}}         |
| Invoice Prefix	    | {{$company->invoice_prefix}}  |
| Postal Address            | {{$company->postal_address}}  |
| Bank Details              | {{$company->bank_details}}    |
| Mail From Email           | {{$company->mail_from_email}} |
| Mail From Name            | {{$company->mail_from_name}}  |
@endcomponent

Please, keep this credentials saved somewhere safe to avoid any sort of data peeping.
We hope you will be enjoying the journey with us.

Thanks.<br>
{{ str_replace('-', ' ', config('app.name')) }}

@slot('header_logo')
<img style="vertical-align: middle; max-height: 40px;" src="{{asset($company->getFirstMediaUrl('logo'))}}" alt="">
@endslot

@slot('footer')
        This mail is being sent only to you for your future assistance respecting your data right. To learn more please visit our
        <a href='{{route('company.privacy-policy')}}' title='Privacy Policy'>privacy policy</a> page.
@endslot

{{-- @slot('subcopy')
        This mail is being sent only to you for your future assistance respecting your data right. To learn more please visit our
        <a href='{{route('company.privacy-policy')}}' title='Privacy Policy'>privacy policy</a> page.
@endslot --}}


@endcomponent
