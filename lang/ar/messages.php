<?php
return [
    "old_version" => "📱✨ تحديث هام! يتوفر الآن إصدار جديد من تطبيقنا على App Store و Google Play. يرجى تحميل التطبيق الجديد للوصول إلى جميع الدورات ومتابعة رحلتك التعليمية. ملاحظة: لن يكون حسابك على هذا الإصدار متاحًا قريبًا، وستتم عملية تسجيل الخروج بشكل دائم. تأكد من الانتقال إلى التطبيق الجديد لمتابعة التعلم دون أي انقطاع. 🌟",
    "error" => [
        "server_error" => "عذرًا! يبدو أن خادمنا واجه خطأ غير متوقع. نأسف للإزعاج الذي قد يسببه هذا لك. تم إبلاغ فريقنا ويعمل بجد لإصلاحه. في الوقت الحالي ، يمكنك محاولة تحديث الصفحة أو العودة لاحقًا. شكرًا لصبرك وتفهمك",
        "not_found" => "404 ضائع في البرية الرقمية 🌲🔍 عذرًا! يبدو أن المسار الذي تبحث عنه قد اختفى في العالم الرقمي. ربما حان الوقت لتتبع خطواتك مرة أخرى أو استكشاف مسار جديد؟ إذا كنت بحاجة إلى توجيه ، ففريقنا من الحراس الرقميين هنا لمساعدتك في العودة إلى الحضارة. 🗺️",
        "activation_code_not_found" => "⚠️ فشل التنشيط! ⚠️
الرمز التنشيط الذي قدمته للدورة غير موجود. يرجى التحقق مرة أخرى من الرمز والمحاولة مرة أخرى. إذا واجهت مشاكل مستمرة ، يرجى الاتصال بالدعم للمساعدة.",
        "shared_activation_code_already_used_for_this_course" => "⚠️ فشل التنشيط! ⚠️
تم استخدام الرمز التنشيط المشترك الذي استخدمته بالفعل لتنشيط الدورات ، بما في ذلك هذه الدورة. يمكن استخدام كل رمز مشترك مرة واحدة فقط لكل دورة. إذا كان لديك أي أسئلة أو تحتاج إلى مساعدة ، يرجى عدم التردد في الاتصال بنا.",
        "activation_code_expired" => "⚠️ فشل التنشيط! ⚠️
الرمز التنشيط الذي قدمته للدورة قد انتهت صلاحيته. يرجى الحصول على رمز تنشيط جديد والمحاولة مرة أخرى. إذا كان لديك أي أسئلة أو تحتاج إلى مساعدة إضافية ، يرجى الاتصال بالدعم.",
        "admin_permission" => "عذرًا ، ليس لديك الصلاحيات الكافية للوصول إلى المورد المطلوب",
        "blocked_account" => "🚫 أوه لا! يبدو أننا واجهنا عقبة مؤقتة. 🛑لقد تم حظرك لسبب ما , لا تقلق ، كل حاجز له حل! يرجى التواصل مع فريق الإدارة لحل هذه المشكلة والعودة إلى المسار الصحيح. يتم تقدير صبرك! 🌟",
        "unknown_lesion_type" => "⚠️ خطأ: نوع الدرس غير صالح! ⚠️ لم يمكن إنشاء الدرس لأن نوع الملف المقدم غير مدعوم. أنواع الملفات المقبولة هي PDF والفيديو. يرجى تحميل ملف صالح والمحاولة مرة أخرى. شكرا لك!"
    ],

    "activation_code_controller" => [
        "error" => [
            "select_more_than_one_course" => "الرجاء اختيار دورة واحدة فقط لنوع الرمز الفردي أو التبديل إلى النوع المشترك",
            "select_less_than_two_course" => "الرجاء اختيار أكثر من دورة واحدة لنوع الرمز المشترك أو التبديل إلى النوع الفردي.",
        ],
        "generate_codes_successfully" => "تم إنشاء رموز التنشيط بنجاح",
    ],

    "auth_controller" => [
        "error" => [
            "credentials_error" => "يبدو أن رمزك السري يلعب لعبة الاختباء مع خوادمنا. دعونا نحاول إقناعه بالخروج معًا.",
            "block_account_while_login" => "🚫 أوه لا! يبدو أنك قمت بتسجيل الدخول من جهاز مختلف. لأمانك ، قمنا بحظر الوصول مؤقتًا.",
        ],
        "device_id_unique" => "لقد قمت مسبقاً بانشاء حسابك من هذا الهاتف , اذا كنت قد نسيته تواصل مع المدير ليساعدك في استرجاعه",
        'register' => "أحسنت! أنت الآن جزء من العائلة. دعنا نجعل السحر يحدث!",
        "login" => "مرحبًا :user_name ! هل عدت للمزيد؟ اختيار ممتاز! دعونا نجعل اليوم أفضل حتى من الأمس.",
        "logout" => "كان من دواعي سرورنا وجودك هنا. اعتن بنفسك وعد قريبًا للمزيد.",
    ],

    "category_controller" => [
        "create_category" => "🎉 تم إنشاء فئة جديدة! 📦 لننظم محتوانا!",
        "update_category" => "🔄 تم تحديث الفئة! 🚀 نحافظ على الأمور جديدة ومنظمة!",
        "delete_category" => "🗑️ تم حذف الفئة! 🚫 تنظيف الربيع قيد التقدم! "
    ],
    "chapter_controller" => [
        "create" => "📚 تمت إضافة فصل جديد! 🌟 لنوسع عالم قصتنا!",
        "update" => "🔄 تم تحديث الفصل! 🔍 نحسن رحلتنا السردية!",
        "delete" => "🗑️ تم حذف الفصل! 🚫 نحافظ على قصتنا مُركَّزة ومرتبة!",
        "visibility_switch" => "👁️‍🗨️ تم تبديل رؤية الفصل! 🌈 نسلط الضوء على تطورات قصتنا الأخيرة! "
    ],

    "choice_controller" => [
        "create" => "🎲 تمت إضافة خيار جديد! 🌟 نوسع خياراتنا للاستكشاف!",
        "update" => "🔄 تم تحديث الخيار! 🔍 نقوم بضبط رحلتنا في اتخاذ القرارات!",
        "delete" => "🗑️ تم حذف الخيار! 🚫 نحافظ على خياراتنا موجزة وملائمة!",
        "visibility_switch" => "👁️‍🗨️ تم تبديل رؤية الخيار! 🌈 نضيء الضوء على المسارات المختارة!",
        "make_choice_true" => "✅ تم تأكيد الخيار! 🎉 نتبنى المسار المختار بثقة!"
    ],

    "course_controller" => [
        "error" => [
            "invisible_course" => "🔍 تحديث رؤية الدورة! 📚 الدورة التي تحاول الوصول إليها غير مرئية حاليًا. إذا كنت تعتقد أن هذا خطأ أو لديك أي أسئلة ، يرجى التواصل مع المسؤول للحصول على مساعدة إضافية.",
            "user_already_enrolled" => "⚠️ فشل التسجيل! ⚠️
أنت بالفعل مسجل في الدورة :course_name لا يُسمح بالتسجيل المتكرر. إذا كانت لديك أي أسئلة أو استفسارات ، يرجى الاتصال بالدعم للحصول على المساعدة.",
            "already_enrolled" => "🛑 تم اكتشاف تكرار التسجيل! 📚 المستخدم :username مسجل بالفعل في :course_name. يرجى مراجعة سجلات التسجيل لتجنب التكرارات.",
            "one_category_at_least" => "⚠️ خطأ: فشل إنشاء الدورة! ⚠️ للمتابعة ، يرجى تقديم فئة واحدة على الأقل للدورة الجديدة. الفئات ضرورية لتنظيم محتوى التعليم لدينا. يرجى المحاولة مرة أخرى مع المعلومات الضرورية. شكرا لك! ",
            "one_teacher_at_least" => "⚠️ خطأ: فشل إنشاء الدورة! ⚠️ للمتابعة ، يرجى تعيين مدرس واحد على الأقل للدورة الجديدة. المدرسون ضروريون لتوجيه رحلات تعلم طلابنا. يرجى المحاولة مرة أخرى مع المعلومات الضرورية. شكرا لك! ",
            "wrong_match_course_with_code" => "⚠️ فشل التنشيط! ⚠️
الرمز التنشيط الذي قدمته لا يمكن استخدامه لتنشيط الدورة المطلوبة. يرجى التأكد من أنك قد أدخلت الرمز الصحيح أو الاتصال بالدعم للمساعدة. شكرا لتفهمك.",
        ],
        "create" => "📚 تم إنشاء دورة جديدة! 🚀 تم إنشاء الدورة :course_name بنجاح. حان الوقت لتشكيل العقول وتحفيز التعلم!",
        "update" => "🔄 تم تحديث الدورة! 📚 تم تحديث الدورة :course_name بنجاح بأحدث المعلومات. لنستمر في إثراء العقول وتمكين المتعلمين!",
        "delete" => "🧹 تم مسح الدورة! 📝 نحن نفسح المجال لجهود تعليمية جديدة. ",
        "cancel_enrolment" => "🚫 تم إلغاء التسجيل بنجاح",
        "visibility_switch" => "👁️‍🗨️ تم تبديل رؤية الدورة! 🌟 تم تحديث الرؤية. لنواصل تشكيل منظرنا التعليمي!",
        "free_switch" => "💰 تم تحديث حالة الحرية! 💸 الدورة الآن :status. نحن ملتزمون بتوفير التعليم المتاح.",
        "manual_enrolled_successfully" => "✅ تم اكتمال عملية التسجيل بنجاح! 📚 تم تسجيل :username بنجاح في :course_name. تم تحديث قائمة الدورات.",
        "enroll_successfully" => "✅ تم التسجيل بنجاح! 📚
تهانينا! لقد تم تسجيلك بنجاح في الدورة :course_name. استعد للانطلاق في رحلة التعلم والاكتشاف. إذا كان لديك أي أسئلة أو تحتاج إلى مساعدة ، فلا تتردد في التواصل. تعلم سعيد!"
    ],

    "course_value_controller" => [
        "create" => "🌟 تمت إضافة قيمة جديدة! 💡 نحتضن مبدأً جديدًا في رحلتنا التعليمية: :value_name . دعونا نزرع ثقافة :value_name.",
        "update" => "🔄 تم تحديث القيمة! 🔧 نتطور في فهمنا لـ :value_name . لنواصل تحسين التزامنا بـ :value_name .",
        "delete" => "🗑️ تم حذف القيمة! 🚫 وداعًا لـ :value_name. ومع وداعنا ، نفسح المجال لرؤى ونمو جديدين.",
    ],

    "exportable_file_controller" => [
        "delete" => "🗑️ تم حذف الملف! 🚫 تمت إزالة الملف :file_name بنجاح من الخادم. نحافظ على تنظيم تخزيننا ونظافته.",
    ],

    "lesion_controller" => [
        'visibility_switch' => "👁️‍🗨️ تم تبديل رؤية الدرس! 🌟 تم تحديث الرؤية. لنواصل توجيه متعلمينا بوضوح وهدف!",
        "delete" => "🗑️ تم حذف الدرس! 🚫 تمت إزالة درس بنجاح من الدورة. نحافظ على منهجنا موجزًا ومركزًا.",
        "create" => "📝 تم إنشاء درس جديد! 🚀 تمت إضافة درس جديد إلى الدورة. دعونا نستكشف مواضيع جديدة مثيرة ونوسع معرفتنا!",
        "update" => "🔄 تم تحديث الدرس! 📝 تم تحديث الدرس بنجاح بأحدث التغييرات. لنتأكد من أن محتوانا مليء بالحيوية والمعرفة!",
    ],

    "news_controller" => [
        "create" => "📰 تمت إضافة خبر جديد إلى شريط الأخبار! 🌟 ابق مُحدثًا بآخر التطورات.",
        "update" => "🔄 تم تحديث عنصر الخبر في شريط الأخبار! 📝 نبقي جمهورنا مطلعين بمحتوى جديد.",
        "delete" => "🗑️ تمت إزالة عنصر الخبر من شريط الأخبار! 🚫 نحن نفسح المجال للتحديثات الجديدة.",
        "visibility_switch" => "👁️‍🗨️ تم تبديل رؤية عنصر الخبر في شريط الأخبار! 🌈 نضيء الضوء على أمور هامة."
    ],

    "notification_controller" => [
        "send_successfully" => "✉️ تم إرسال الإشعار بنجاح! 🚀 تم تسليم رسالتك بنجاح إلى المستلمين المقصودين. لنواصل التواصل!",
    ],

    "question_controller" => [
        "create" => "📝 تم إنشاء سؤال جديد! 🌟 دعونا نوسع قاعدة معرفتنا باستفسارات فطنة.",
        "update" => "🔄 تم تحديث السؤال! 🔍 نحافظ على سؤالاتنا ذات الصلة والمثيرة.",
        "delete" => "🗑️ تم حذف السؤال! 🚫 نحن نفسح المجال للاستفسارات الجديدة.",
    ],

    "quiz_controller" => [
        "error" => [
            "quiz_added_before_to_chapter" => "⚠️ الاختبار مضاف بالفعل إلى الفصل! ⚠️ الاختبار الذي تحاول إضافته موجود بالفعل في هذا الفصل. يرجى ضمان تماثل المحتوى وتجنب التكرار. إذا كان لديك أي أسئلة ، فلا تتردد في التواصل.",
        ],
        "create" => "📝 تم إنشاء اختبار جديد! 🌟 دعونا نبدأ المرح والتعلم مع هذا الاختبار المثير. استعد لتحدي وجذب جمهورك!",
        "update_quiz_in_chapter" => "🔄 تم تحديث الاختبار في الفصل! 📝 تم تحديث محتوى الاختبار لتعزيز تجارب التعلم داخل الفصل. لنحافظ على جذب وتحفيز متعلمينا!",
        "update" => "🔄 تم تحديث الاختبار! 📝 تم تحديث الاختبار بأسئلة جديدة وتحسينات. استعد لتجربة تعلم محسنة!",
        "delete" => "🗑️ تم حذف الاختبار! 🚫 تمت إزالة الاختبار. نحن نفسح المجال لتحديات وفرص تعلم جديدة.",
        "delete_from_chapter" => "🗑️ تم حذف الاختبار من الفصل! 🚫 تمت إزالة الاختبار من هذا الفصل. ضبط المحتوى لتناسب أهداف التعلم لدينا.",
        "questions_added" => "✅ تمت إضافة الأسئلة بنجاح إلى الاختبار! 🌟 تم دمج الأسئلة المحددة بنجاح في الاختبار المطلوب. الاختبار مثر بالآن بمحتوى جديد.",
        "delete_question_from_quiz" => "🗑️ تم حذف السؤال من الاختبار! 🚫 تمت إزالة السؤال من الاختبار. ضمان تناغم محتوى الاختبار تمامًا مع أهداف التعلم لدينا.",
        "visibility_update" => "👁️‍🗨️ تم تحديث رؤية الأسئلة في الاختبار! 🌟 تم تعديل رؤية الأسئلة داخل الاختبار. لنضمن تجربة تعلم سلسة لمشاركينا!",
        "quiz_to_chapter_successfully" => "✅ تمت إضافة الاختبار بنجاح إلى الفصل! 📝 تم دمج الاختبار بنجاح في الفصل. الآن هو جاهز لجذب وتحدي متعلمينا.",
    ],

    "statistics_controller" => [
        "reset_successfully" => "✅ تمت إعادة ضبط الإحصائيات بنجاح! 📊 تمت إعادة تعيين جميع البيانات ، مما يضمن بداية جديدة لتتبع التقدم والأداء. ",
    ],

    "user_controller" => [
        "create" => "✅ تم إنشاء الحساب بنجاح! 🎉 تم إنشاء الحساب الجديد بنجاح.",
        "update_profile" => "✅ تم تحديث الملف الشخصي بنجاح! 🔄 تم تحديث معلومات ملفك الشخصي بنجاح. شكرًا لك على الحفاظ على تفاصيلك حديثة! ",
        "block_switch" => "✅ تم تحديث حالة حظر الحساب بنجاح! 🚫 تم تبديل حالة الحظر للحساب بنجاح. ",
        "delete_user" => "✅ تمت إزالة الحساب والبيانات ذات الصلة بنجاح! 🗑️ تم حذف الحساب بالإضافة إلى جميع البيانات المرتبطة به. الانتهاء من التنظيف! ",
        "reset_password" => "✅ لقد قمنا بإعادة تعين كلمة المرور الخاصة بهذا الحساب , تأكد من ابقاء كلمة المرور الخاصة بك في مكان امن"
    ],

    "user_watch_controller" => [
        "watch_registered" => "👀 تم تسجيل مشاهدة الفيديو بنجاح! 📹 تم تسجيل مشاهدتك للفيديو بنجاح من قبل النظام. استمر في استكشاف محتوانا! ",
    ],

    "video_controller" => [
        "link_not_correct" => "⚠️ خطأ: الرابط غير صحيح! ⚠️ الرابط الذي قدمته غير صحيح أو مكسور. يرجى التحقق مرة أخرى من عنوان URL والمحاولة مرة أخرى. إذا واجهت مشكلة مستمرة ، يرجى الاتصال بالدعم للحصول على المساعدة. شكرًا لك! ",
    ],

];
