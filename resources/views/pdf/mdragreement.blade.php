<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="{{ public_path().'/css/deedpaper.css' }}">

<style type="text/css">
	body {
		font-size:14px;
		line-height:22px;
		font-family:'bangla-bold', sans-serif;
	}
	.cTr{
		width: 100%;
	}
	.fl{
		float: left;
	}
	.bbt{
		font-size: 12px;
		font-family:english;
		border-bottom:2px dotted #000;
	}
	.bb{ border-bottom:1px dotted #000;}

</style>

</head>
<body>
	<div style="height:00px">&nbsp;</div>
    <h1 class="text-center mainTitle"><span>ফরম-৭০<br>শিক্ষাধীনতা চুক্তিপত্র</span></h1>
    
	<div class="pb-50">পরম করুনাময় আল্লাহর নামে অত্র চুক্তিনামাটি নিম্নোক্ত পক্ষগনের মতৈক্যের ভিত্তিতে অদ্য {{date('d F Y', strtotime($AppointmentLetter[0]->application_details[0]->effectivedate) )}} তারিখ মেসার্স ঢাকা আইসক্রীম ইন্ডাস্ট্রীজ লিমিটেড, ঠিকানাঃ ৮০ শহীদ তাজউদ্দীন আহমেদ স্মরণী, তেজগাঁও শিল্প এলাকা, ঢাকা-১২০৮ । অতঃপর মালিক হিসাবে
	উল্লিখিত এবং নাম  : Mr. {{$AppointmentLetter[0]->application_details[0]->applicant_name}} <br>
	পিতার নামঃ S/O : {{$AppointmentLetter[0]->application_details[0]->applicant_fathers_name}} <br>
	মাতার নামঃ .........................................................................................................<br>
	বর্তমান ঠিকানাঃ Address: {{$AppointmentLetter[0]->application_details[0]->applicant_address}}<br>
	স্থায়ী ঠিকানাঃ  Address: {{$AppointmentLetter[0]->application_details[0]->applicant_address}} <br>
	অতঃপর শিক্ষাধীন হিসাবে উল্লিখিত এবং জনাব/জনাবা Mr. {{$AppointmentLetter[0]->application_details[0]->applicant_name}} <br>
	বর্তমান ঠিকানাঃ  Address: {{$AppointmentLetter[0]->application_details[0]->applicant_address}}<br>
	স্থায়ী ঠিকানাঃ  Address: {{$AppointmentLetter[0]->application_details[0]->applicant_address}} <br>
	এর মধ্যে চুক্তি সম্পাদিত হলো।
	যেহেতু এই শিক্ষাধীন Mr. {{$AppointmentLetter[0]->application_details[0]->applicant_name}} (পেশার নাম) Market Development Representative. 
	(প্রতিষ্ঠানের নাম) এই গ্র্যাজুয়েট/সুপারভাইজার /ট্রেড এপ্রেনটিস হিসাবে প্রশিক্ষিত হতে আগ্রহী। <br>
	অতএব, এতদ্বারা মালিক, উক্ত আগ্রহী বিবেচনা করে শিক্ষানবীসকে অত্র প্রতিষ্ঠানে শিক্ষানবিসী কার্যক্রমের শর্তাবলী অনুসারে এ সাপেক্ষে প্রশিক্ষ‍ণ গ্রহনের জন্য নির্বাচিত করলেন।<br>
	শিক্ষাধীন Mr. {{$AppointmentLetter[0]->application_details[0]->applicant_name}} বিশ্বস্ততার সাথে এবং অধ্যবসায়ের সাথে এবং অত্র চুক্তিতে বর্ণিত শর্তাবলী অনুসারে কাজ করতে সম্মত হয়েছে।<br>
	শিক্ষাধীন পিতা/মাতা/ আইনানুগ অভিভাবক এতদ্বারা শিক্ষাধীন বিশ্বস্ততার সাথে অত্র চুক্তি মেনে  চলছেন কিনা এবং কর্তব্য পালন করছেন কিনা তা দেখার জন্য নিজেদেরকে দায়বদ্ধ করলেন।<br>
	মালিকের সাথে উপরিউক্ত কার্যক্রমে ধার্যকৃত শিক্ষাধীনতার মেয়াদ শুরু হবে {{date('d F Y', strtotime($AppointmentLetter[0]->application_details[0]->effectivedate) )}}  তারিখে এবং সমাপ্তি হবে {{date('d F Y', strtotime($AppointmentLetter[0]->application_details[0]->effectivedate . " +2 year") )}} তারিখে । <br>
	অত্র শিক্ষাধীনতা কেবলমাত্র মালিক ও শিক্ষাধীনের মধ্যে পারস্পরিক সমঝোতাক্রমে এবং যোগ্য  কর্তৃপক্ষের পূর্বানুমোদন সাপেক্ষে পরিসমাপ্ত হতে পারবে। <br>
	উপরিউক্ত বর্ণনা সত্ত্বেও, শিক্ষাধীন তার প্রশিক্ষণ কাজে অসন্তোষজনক অগ্রগতি প্রদর্শন করলে অথবা শৃংখলাজনিত কারনে আইন অনুসারে মালিক কর্তৃক যোগ্য কর্তৃপক্ষের সাথে পূর্বাহ্নে আলোচনাক্রমে শিক্ষাধীনতার অবসান করার ক্ষমতা থাকবে। <br><br>
	মালিক কর্তৃক অত্র চুক্তির শর্তাবলী পূরণ করা সত্ত্বেও কোনো ক্ষেত্রে শিক্ষাধীন তার প্রশিক্ষন গ্রহণের মেয়াদের মধ্যে এককভাবে মালিকের চাকরি ত্যাগ করলে শিক্ষাধীন চুক্তিভঙ্গের পূর্ববর্তী ১২ মাসে মালিক কর্তৃক বৃত্তি হিসাবে খরচকৃত অর্থ মালিককে ফেরত প্রদান করতে বাধ্য থাকবেন। <br><br>
	যে কোনো পক্ষ, যে কোনো সময় কোনো বিষয়ে মতানৈক্য দেখা দিলে অত্র চুক্তির যে কোনো অংশের ব্যাখ্যার জন্য যোগ্য কর্তৃপক্ষের সাথে পরামর্শ করতে পারবেন এবং যোগ্য কর্তৃপক্ষের ব্যাখ্যা অপর পক্ষ  মেনে চলতে বাধ্য থাকবেন । <br><br>
	এতদ্বার্থে পক্ষগণ এতদ্বারা অত্র চুক্তিপত্রের তিন কপিতে উহাদের নাম, স্বাক্ষর ও সীলমোহর প্রদান করলেন।

	</div>
    <div style="height:30px">
    	<table>
    		<tr>
    			<td width="25%">(শিক্ষাধীন শ্রমিকের স্বাক্ষর)</td>
    			<td align="right" width="75%">মালিকের পক্ষে স্বাক্ষর ও সীলমোহর</td>
    		</tr>
    	</table>
	</div>
    <div style="height:30px">{{$AppointmentLetter[0]->application_details[0]->applicant_address}}</div>
    
</body>
</html>
