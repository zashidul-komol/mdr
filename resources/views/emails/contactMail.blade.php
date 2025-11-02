@component('mail::button', ['url' => 'mailto:'.$data['complainant_email']])
Reply to {{$data['complainant_email']}}
@endcomponent
প্রিয় সহকর্মী,<br><br>
{{$data['complain_date']}} মিনিটে একটি অভিযোগ / পরামর্শ প্রেরিত হয়েছে। এই বিষয়ে বিস্তারিত দেখতে নিম্নলিখিত লিংক এ ক্লিক করুন।<br><br>
http://complain.polarbd.com/customerComplains/view_customer_complain/{{$data['id']}}<br><br>
আপনাকে উক্ত অভিযোগের বিষয়ে পুরোপুরি অবহিত হয়ে, আগামী ৫ কর্ম দিবসের  মধ্যে যথাযথ উত্তর প্রদানের ব্যবস্থা গ্রহন করার জন্য বিনীত অনুরোধ করা যাচ্ছে।<br><br>

ধন্যবাদান্তে<br><br>

পোলার আইস ক্রীম

<br><br>
<a href='http://www.polarbd.com' target=_blank><img src='http://www.polarbd.com/expressdelight/images/Polar-Logo-Bangla.jpg' border=0 alt='www.polarbd.com'></a><br><br>



