<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
      <style>
        body {
         font-family: dejavusanscondensed;
         direction: rtl;
        }
      </style>
   </head>
   <body>
      <table style="width: 100%;">
         <tbody>
            <tr >
            <td style="width: 50%;">
                  <div style="text-align: right;" dir="rtl">
                     <h6><b>عقد شراكة تجارية</b> </h6>
                     <p>أنه في هذا اليوم {{ (!empty($arabic)) ? $arabic['day'] :'' }} من شهر {{ (!empty($arabic)) ? $arabic['month'] : '' }} من عام {{ (!empty($arabic)) ? $viewData[0]->year : '' }} تم توقيع هذا العقد بين كل من:</p>
                  </div>
               </td>  
               <td style="width: 50%;" dir="ltr">
                  <h6><b>Commercial Partnership Contract</b> </h6>
                  <p>This agreement was concluded on the {{ (empty($arabic)) ? $viewData[0]->day : "" }} day of {{ (empty($arabic)) ? $viewData[0]->month : "" }} month, {{ (empty($arabic)) ? $viewData[0]->year : "" }} Between:</p>
               </td>
            </tr>
            <tr>
             
               <td >
                  <p style="text-align: right;" dir="rtl"><b>١- شركة سلوانا دايموند للاستثمار </b></p>
               </td>
               <td dir="ltr" >
                  <p><b>1- Silwana Diamond Investment </b></p>
               </td>
            </tr>
            <tr>
             
               <td>
                  <p style="text-align: right;" dir="rtl">و يشار إليها ب(الطرف الأول)</p>
               </td>
               <td dir="ltr">
                  <p>And referred to hereinafter by the (First Party)</p>
               </td>
            </tr>
            <tr>
              
               <td>
                  <p style="text-align: right;" dir="rtl">٢- و</p>
               </td>
               <td dir="ltr">
                  <p>2- And </p>
               </td>
            </tr>
            <tr>
              
               <td>
                  <p style="text-align: right;" dir="rtl"><b>السيد ة / </b> {{ (!empty($arabic))  ? $arabic['customerFname'].''.$arabic['customerLname'] : '' }}</p>
               </td>
               <td dir="ltr">
                  <p><b>Mr.</b> {{ (empty($arabic)) ? $viewData[0]->customerFname .' '.  $viewData[0]->customerLname : '' }}</p>
               </td>
            </tr>
            <tr>
              
               <td>
                  <p style="text-align: right;" dir="rtl"><b>الجنسية: </b> {{ (!empty($arabic))  ? $arabic['nationality'] : '' }}</p>
               </td>
               <td dir="ltr">
                  <p><b>Nationality</b> {{ (empty($arabic)) ? $viewData[0]->nationality : '' }}</p>
               </td>
            </tr>
            <tr>
              
               <td>
                  <p style="text-align: right;" dir="rtl"><b>رقم الهوية : </b><?=(!empty($arabic)) ? $arabic['national_id'] : '' ?></p>
               </td>
               <td dir="ltr">
                  <p><b>ID No:  </b> {{ (empty($arabic)) ? $viewData[0]->national_id : '' }}</p>
               </td>
            </tr>
            <tr>
             
               <td>
                  <p style="text-align: right;" dir="rtl"><b>تاريخ الانتهاء: </b>  {{ (!empty($arabic))  ? $arabic['dob'] : '' }}</p>
               </td>
               <td dir="ltr">
                  <p><b>Date of Expiry:   </b>  {{ (empty($arabic)) ? date('d-F-Y',strtotime($viewData[0]->date_of_expiry)) : '' }} </p>
               </td>
            </tr>
            <tr>
             
               <td>
                  <p style="text-align: right;" dir="rtl"><b>تاريخ الميلاد</b> {{ (!empty($arabic))  ? $arabic['date_of_expiry'] : '' }}</p>
               </td>
               <td dir="ltr">
                  <p><b>Date of Birth: </b> {{ (empty($arabic)) ?  date('d-F-Y',strtotime($viewData[0]->dob)) : '' }}</p>
               </td>
            </tr>
            <tr>
             
               <td>
                  <p style="text-align: right;" dir="rtl"><b>و يشار إليها ب(الطرف الثاني)</b></p>
               </td>
               <td dir="ltr">
                  <p>And referred to hereinafter by the (Second Party)</p>
               </td>

            </tr>
            <tr>
              
               <td>
                  <h6 style="text-align: right;"><b>تمهيد</b></h6>
                  <p style="font-size: 16px;margin-bottom:25px;text-align: right;" dir="rtl">الطرف الأول شركة سلوانا دايموند للاستثمار المرخصة في دولة الإمارات العربية المتحدة والمسجلة لدى الدائرة الاقتصادية بدبي بسجل تجاري رقم (٨١٨٩٤٦) ويشمل نشاطها التجارة العامة و مزاولة كافة الأنشطة المتعلقة بهذه الأغراض أو المتصلة بها و هي رائدة بالإنتاج و الإتجار بالثروة الحيوانية و الزراعية من خلال  مشاريع الهيدروپونيك و الأكواپونيك و الآيروپونيك المستحثة من الناحية التكنولوجية لمنتجي الماشية و المزارعين والمستفادة لإطعام العالم بالأغذية الطازجة من مزارع سلوانا.</p>
                  <p style="font-size: 16px;margin-bottom:25px;text-align: right;" dir="rtl">الطرف الثاني شريك في التجارة ولديه المقدرة المالية ويرغب في الدخول مع  الطرف الأول في شراكة في مشاريعه الزراعية و الحيوانية  التجارية  بمبلغ من المال لصالحه كما يراه مناسبا ومحققا للأرباح في المجالات المذكورة أعلاه. </p>
                  <p style="font-size: 16px;margin-bottom:25px;text-align: right;" dir="rtl">وبناء عليه يتم الاتفاق بين الطرفين تبعاً لما ذكر أعلاه كالتالي:</p>
               </td>

               <td dir="ltr"> 
                  <h6><b>Preamble</b></h6>
                  <p style="font-size: 16px;margin-bottom:25px;" >The First Party, Silwana Diamond Investment Company, is licensed in the United Arab Emirates and registered with the Dubai Economic Department with a commercial registration number (818946). Its activity includes general trade and practicing all activities related to these purposes or connected to it and it is a pioneer in production and trade in livestock and agriculture through Hydroponic and Aquaponics and Aeronautics projects, which are technologically induced for livestock and farmers producers who are interested in feeding the world fresh food from Silwana farms.</p>
                  <p style="font-size: 16px;margin-bottom:25px;" >The Second Party is a partner in trade and has the financial ability and wants to enter and engage with the First Party in agricultural and animal commercial projects partnership with an amount of money in his favor as he deems appropriate and generates profits in the above-mentioned areas</p>
                  <p style="font-size: 16px;margin-bottom:25px;" >Therefore, an agreement is reached between the First and the Second Party according to the above-mentioned as follows:</p>
               </td>
            </tr>
            <tr>
             
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند الأول: التفسير</strong></h6>
                  <p  style="text-align: right;" dir="rtl">يعتبر التمهيد السابق جزءً لا يتجزأ من هذا الاتفاق ويقرأ ويفسر معه.</p>
               </td>

               <td dir="ltr">
                  <h6><strong>Item (1): Illustration </strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">The above-mentioned Preamble shall be deemed as an integral part of this agreement and shall be read and interpreted along with it.</p>
               </td>
            </tr>
            <tr>
              
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند الثاني: مدفوعات رأس المال التجاري</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">رأس المال التجاري المقدم من الطرف الثاني كشريك تجاري بقيمة المبلغ المقدم.</p>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">وافق وقبل الطرف الثاني على شراكته التجارية بمبلغ وقدره {{ (!empty($arabic)) ? $arabic['amount'] : '***' }} ( {{ (!empty($arabic)) ? $arabic['amount'] : '***' }} درهم إماراتي) وسلمه للطرف الأول الذي يقوم بأداء عمله كتاجر مشاريع بغرض المشاركة في تجارة سلوانا دايموند الزراعية والحيوانية، وهذا المبلغ يعتبر كإيداع مبدئي يمكن زيادته طبقاً للبنود المنظمة لهذا الشأن.</p>
               </td>
               <td dir="ltr">
                  <h6><strong>Item (2): Commercial Capital Amount </strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">The above-mentioned Preamble shall be deemed as an integral part of this agreement and shall be read and interpreted along with it.</p>
                  <p style="font-size: 16px;margin-bottom:25px;">The second party has agreed and accepted to pay a commercial capital of {{ (empty($arabic)) ? $viewData[0]->amount : '***' }} Dirhams Emirati ({{ (empty($arabic)) ? $viewData[0]->amount : '***' }}Dirhams Emirati) and handed it over to the First Party which will carry on its commercial business as a producer and trader for the purpose of participating in the commercial livestock and agricultural products of Silwana Dimond pursuant to the above-mentioned preamble. And this amount will be considered as initial commercial capital and can be increased in accordance with the items regulating this matter.
                  </p>
               </td>
            </tr>
            <tr>
             
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند الثالث: الإدارة و المخاطر</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">يتعهد الطرف الأول كتاجر مشاريع ببذل الجهد في إدارة تجارة رأس المال بشكل محترف بضمان تحقيق أعلى الأرباح الممكنة وتجنب الخسائر وتقليل المخاطر. كما يضمن الطرف الأول المحافظة على رأس مال الطرف الثاني والمذكور في البند الثاني من هذا العقد.</p>
               </td>

               <td dir="ltr">
                  <h6><strong>Item (3): Administration and Risk </strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">The First Party as a commercial trader undertakes to exert its efforts to manage the commercial capital professionally to ensure achieving the highest possible profits and to avoid any loss and to reduce risks. The first party also shall guarantee to maintain the commercial capital mentioned in item (2) of this agreement.</p>
               </td>

            </tr>
            <tr>
              
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند الرابع: الأرباح و الخسائر</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">يتوقع الطرف الأول دخلاً  شهرياً يتراوح ما بين خمسة في المائة (٠٥٪) الى عشرون في المائة (٢٠٪) مع المحافظة على رأس مال الطرف الثاني بصفر في المائة (٠٪) خسائر.</p>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">و تم الاتفاق علي توزيع ريع المشاريع مناصفة بين الطرف الأول و الطرف الثاني. حيث سيحصل الطرف الأول على خمسون في المائة (٥٠٪) من المدخولات الشهرية و يحصل الطرف الثاني على خمسون في المائة (٥٠٪) من متبقي المدخولات الشهرية. ويتعهد الطرف الأول بتسليم المدخولات الشهرية الى الحساب البنكي الذي يحدده الطرف الثاني في بداية كل شهر ميلادي.</p>
               </td>

               <td dir="ltr">
                  <h6><strong>Item (4): Profit and Loss </strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">The First Party expects a monthly income between five Percent (05%) to Twenty Percent (20%) while maintaining the commercial capital of the Second Party with Zero percent (0%) losses.</p>
                  <p style="font-size: 16px;margin-bottom:25px;">And it was agreed to distribute the proceeds of the projects equally between the First Party and the Second Party. Where the First Party will get Fifty Percent (50%) of the monthly income and the second party will get Fifty Percent (50%) of the remaining monthly income. The First Party undertakes to deliver the monthly income to the Second Party bank account specified by the Second Party at the beginning of each Gregorian month. </p>
               </td>

            </tr>
            <tr>
             
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند الخامس: حالات الوفاة (لا قدر الله)</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">في حالة إخطار الطرف الأول  كتابياً بوفاة الطرف الثاني (لا قدر الله) في أثناء فترة سريان هذا التعاقد يقوم الطرف الأول بتجميد الشراكة التجارية المذكورة أعلاه، ويلتزم الطرف الأول برد رأس المال التجاري والمدخولات الشهرية إن وجدت للخلف العام والخاص (للطرف الثاني). أي، الورثة الشرعيين أو من يوصي به الطرف الثاني كتابةً عند توقيع هذا العقد بالنسبة المئوية التي ينص عليها، ويقوم بالتوقيع عليها بكامل إرادته، أو من يصدر لصالحه حكماً قضائياً نهائياً باتاً بأحقيته في هذا المبلغ أو جزءً منه، أو ستحال الورثة المستحقة من الطرف الثاني التي هي بحوزة الطرف الأول الي المصرف المركزي لحين تحصيلها بواسطة ورثته.</p>
               </td>

               <td dir="ltr">
                  <h6><strong>Item (5): Death Cases (Allah Forbid) </strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">In case that the First Party is notified, in writing, of the death of the Second Party (Allah Forbid) during the period of the validity of this contract, the First Party will freeze the above-mentioned commercial capital and monthly return. And, the First Party will be obliged to return the commercial capital and the monthly income, if any, to the public and private heirs of the Second Party. In other words, the legal heirs or whoever is recommended by the Second Party in writing when signing this contract and in the percentage stipulated by the Second Party, and signed it at his full will, or whoever issued in his/her favor a final judicial ruling for his/her right to this amount or part of it, or the amount due for The Second Party that is in the possession of the First Party will be delivered to the Central Bank until it is collected by the Second Party heirs.</p>
               </td>

            </tr>
            <tr>
              
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند السادس: سرية الحسابات</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">يلتزم الطرف الأول و الطرف الثاني بسرية بنود هذا العقد باستثناء صدور أحكاماً قضائية واجبة النفاذ، أو قرارات من النائب العام، أو احدى الجهات القضائية المنوط بها الكشف عن سرية هذه الاتفاقية.</p>
               </td>

               <td dir="ltr">
                  <h6><strong>Item (6): Confidentiality of Accounts</strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">The two parties shall comply with the confidentiality of the items of this contract, except for any enforceable judicial judgments or decisions made by the attorney general or any judicial authorities that require to disclose the confidentiality of this agreement.</p>
               </td>

            </tr>
            <tr>
            
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند السابع: حظر المنافسة</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">يتعهد الطرف الثاني بعدم القيام بأي عمل من الأعمال أو المشاريع التي يقوم بها الطرف الأول أو أن ينافسه بالغرض المخصص لها. وفي حال ثبوت مخالفة الطرف الثاني لهذا البند يحق للطرف الأول فسخ العقد ومطالبته بالتعويضات الناتجة عن تصرفه.</p>
               </td>

               <td dir="ltr">
                  <h6><strong>Item (7): Prohibition of Competition </strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">The second party MUST NOT carry out any business or projects practiced by the First Party and not to compete with the First Party for the purpose allocated for the First Party. Should the First Party discover any breach of this item, the First Party shall have the right to terminate this contract and to claim appropriate indemnity for such action to be paid from the Second Party.</p>
               </td>

            </tr>
            <tr>
              
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند الثامن: الانسحاب و التنازل عن العقد</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">١- لا يحق لأي طرف من الطرفين أن ينسحب من الشراكة قبل نهاية مدتها.</p>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">٢- لا يحق لأي طرف ان يبيع أو يرهن أو يتنازل عن هذه الشراكة/العقد أو جزء منها/منه بموجب هذا العقد الا بموافقة الطرفين خطياً.</p>
               </td>

               <td dir="ltr">
                  <h6><strong>Item (8): Withdrawal and Assignment of The Commercial Partnership Contract</strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">1- No party has the right to withdrawal from the partnership before the end of its term.</p>
                  <p style="font-size: 16px;margin-bottom:25px;">2- No party has the right to sell or mortgage or assign any of this commercial partnership contract or any part thereof unless it has been approved by the First Party and the Second in writing.</p>
               </td>    

            </tr>
            <tr>
              
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند التاسع: فسخ العقد</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">١-  في حالة فسخ العقد من قبل الطرف الأول قبل نهاية مدة العقد، يلتزم الطرف الأول برد كامل رأس مال الطرف الثاني وما يستحق من أرباح لم تصرف للطرف الثاني بمعدل خمسة في المائة (٥٪) من رأس المال التجاري للطرف الثاني من الشهر التالي لفسخ العقد حتي نهاية مدة العقد.</p>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">٢-  في حالة فسخ العقد من قبل الطرف الثاني قبل نهاية مدة العقد يتم إسترجاع كامل المدخولات الشهرية المستلمة من الطرف الأول و بحوذة الطرف الثاني. و يلتزم الطرف الأول برد رأس مال الطرف الثاني كاملاً بعد إسترداده لجميع مدخولات الطرف الثاني من ريع المشاريع قبل يوم فسخ العقد.</p>
               </td>

               <td dir="ltr">
                  <h6><strong>Item (9): Termination of Contract</strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">1- In case of the termination of this Contract by the First Party before the end of the contract period, the First Party is obligated to return the full commercial capital of the Second Party and the dues it has not paid to the Second Party at a rate of five Percent (5%) of the commercial capital of the Second Party from the month following the dissolution of the contract until the end of the contract period.</p>
                  <p style="font-size: 16px;margin-bottom:25px;">2- In case of the termination of this Contract by the Second Party before the end of the contract period, all the monthly income received and in possession of the Second Party from the First Party will be returned in full to the First Party. The First Party shall be obligated to return the commercial capital of the Second Party in full after recovering all the monthly returns of the Second Party from the proceeds of the projects before the day of the termination of this contract.</p>
               </td>

            </tr>
            <tr>
           
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند العاشر: إنتهاء العقد</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">في حالة إنتهاء مدة العقد يجوز للطرفين تمديد العقد في حالة الإتفاق لمدة مماثلة بنفس الشروط المبينة كتابياً و المدرجة في هذا العقد و إصدار عقد جديد بهذا الشأن. وفي حالة عدم الإتفاق يتم رد رأس المال للطرف الثاني كاملاً إذاناً بإنتهاء العقد المبرم في هذا الاتفاق بين الطرف الأول و الطرف الثاني.</p>
               </td>

               <td dir="ltr">
                  <h6><strong>Item (10): Expiration of the Commercial Partnership Contract </strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">In case of expiration of this commercial partnership contract, the First Party and the Second Party may extend the contract in the event of an agreement for a similar period in the same conditions, set out in writing, included in this contract, and issue a new contract in this regard. In case of non-agreement, the commercial capital will be returned to the Second Party in full, so the end of the contract will be concluded for this contract between the First Party and the Second Party.</p>
               </td>
            </tr>

            <tr>
               <td>
                  <h6  style="text-align: right;"  dir="rtl" ><strong>البند الحادي عشر: مدة العقد</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">إتفق الطرفان على ان تكون مدة العقد سـنة ميلادية واحدة تبدأ من تاريخ {{ (!empty($arabic)) ? $viewData[0]->contract_start_date : "2021/*/*" }} وتنتهي بتاريخ {{ (!empty($arabic)) ? $viewData[0]->contract_end_date : "2021/*/*" }}   </p>
               </td>
               <td dir="ltr">
                  <h6><strong>Item (11): Contract Duration</strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">The First Party and the Second Party agreed that the period of this contract will be For One Georgian calendar year commenced from {{ (empty($arabic)) ? $viewData[0]->contract_start_date : "*/*/2021" }}-- and expires on {{ (empty($arabic)) ? $viewData[0]->contract_end_date : "*/*/2021" }} </p>
               </td>
            </tr>
            <tr>
               
               <td>
                  <h6  style="text-align: right;"  dir="rtl"><strong>البند الثاني عشر: التقارير</strong></h6>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">في مدة لا تتعدي الخمسة أيام الأولي من بداية كل شهر، سيسلم الطرف الأول الطرف الثاني تقريراً عن وضع الطرف الثاني المادي من خلال شراكته التجارية في هذا العقد موضحاً فيه مؤشرات الأداء للعمليات التجارية المنجزة.   </p>
                  <p style="text-align: right;margin-bottom: 25px;" dir="rtl">تحرر هذا العقد من نسختين، كل نسخة بها ستة صفحات، موقعتين من الطرف الأول و الطرف الثاني و تسلم بيد كل طرف نسخة منه للعمل بها عند اللزوم.</p>
               </td>

               <td dir="ltr">
                  <h6><strong>Item (12): Reports</strong></h6>
                  <p style="font-size: 16px;margin-bottom:25px;">During the period of no more than five days of the beginning of each month, The Second Party will receive a commercial report from the First Party showing the commercial contract account and the key performance indicator(s) of the Second Party commercial engagement with the First Party. </p>
                  <p style="font-size: 16px;margin-bottom:25px;">This contract was concluded and issued in Two copies, six pages each, signed by the First Party and Second Party and each party has received One copy to resort to it if or when necessary. </p>
               </td>  

            </tr>
            <tr>
              
               <td>
                  <h6  style="text-align: right;"  dir="rtl" ><strong>الطرف الأول</strong></h6>
                  <h6 style="text-align: right;margin-bottom: 25px;" dir="rtl">شركة سلوانا دايموند للاستثمار </h6>
                  <h6 style="margin-top: 50px;"  dir="rtl"><strong>التوقيع /   </strong></h6>
                  <br>
                  <br>
                  <h6  style="text-align: right;margin-top: 50px;"  dir="rtl" ><strong>الطرف الثاني </strong></h6>
                  <h6 style="text-align: right;margin-bottom: 25px;" dir="rtl">السيد / {{ (!empty($arabic)) ? $arabic['customerFname'] .' '.  $arabic['customerLname'] : '' }} </h6>
                  <br>
                  <br>
                  <h6 style="margin-top: 50px;"  dir="rtl"><strong>التوقيع / </strong></h6>
                  <br>
                  <br>
                  <h6 style="margin-top: 50px;"  dir="rtl"><strong>تحرر في يومه و شهره وعامه في اليوم {{ (!empty($arabic)) ?  $arabic['day'] : '' }} من شهر {{ (!empty($arabic)) ?  $arabic['month'] : '' }} من عام {{ (!empty($arabic)) ?  $arabic['year'] : '' }} </strong></h6>
                  <br>
                  <br>
                  <h6 style="margin-top: 50px;"  dir="rtl"><strong>التاريخ:</strong></h6>
               </td>

               <td dir="ltr">
                  <h6><strong>The First Party</strong></h6>
                  <h6><strong>Silwana Diamond Investment </strong></h6>
                  <h6 style="margin-top: 50px;"><strong>Signature / </strong></h6>
                  <br>
                  <br>
                  <h6 style="margin-top:50px"><strong>The Second Party</strong></h6>
                  <h6><strong>Mr.:  {{ (empty($arabic)) ? $viewData[0]->customerFname .' '.  $viewData[0]->customerLname : '' }}</strong></h6>
                  <br>
                  <br>
                  <h6 style="margin-top: 50px;"><strong>Signature / </strong></h6>
                  <br>
                  <br>
                  <h6 style="margin-top: 50px;"><strong>Issued on the {{ (empty($arabic)) ?  $viewData[0]->day : '' }} day of {{ (empty($arabic)) ?  $viewData[0]->month : '' }} Month of the Year of {{ (empty($arabic)) ?  convertNumberToWord($viewData[0]->year) : '' }} </strong></h6>
                  <br>
                  <br>
                  <h6 style="margin-top: 50px;"><strong>The Date: </strong></h6>
               </td>

            </tr>
         </tbody>
      </table>
   </body>
</html>