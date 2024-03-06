<?php
return [
    "error" => [
        "server_error" => "Oops! It seems like our server encountered an unexpected error. We're sorry for the inconvenience this may have caused you. Our team has been notified and is working diligently to fix it. In the meantime, you can try refreshing the page or coming back later. Thank you for your patience and understanding",
        "not_found" => "404 Lost in the Digital Wilderness 🌲🔍 Oops! The path you're seeking seems to have vanished into the digital ether. Perhaps it's time to retrace your steps or explore a new route? If you need guidance, our digital rangers are here to help you navigate back to civilization. 🗺️",
        "activation_code_not_found" => "⚠️ Activation Failed! ⚠️
The activation code you provided for the course is not found. Please double-check the code and try again. If you continue to experience issues, please contact support for assistance. #ActivationCodeNotFound",
        "shared_activation_code_already_used_for_this_course" => "⚠️ Activation Failed! ⚠️
The shared activation code you used has already been utilized to activate courses, including this one. Each shared code can only be used once per course. If you have any questions or require assistance, please don't hesitate to reach out to us. #SharedCodeAlreadyUsed",
        "activation_code_expired" => "⚠️ Activation Failed! ⚠️
The activation code you provided for the course has expired. Please obtain a new activation code and try again. If you have any questions or need further assistance, please contact support. #ExpiredActivationCode",
        "admin_permission" => "Sorry, you do not have sufficient permissions to access requested resource",
        "blocked_account" => "🚫 Uh-oh! It seems we've hit a temporary roadblock. 🛑 Don't worry though, every blockade has a solution! Please reach out to our admin team to iron out this hiccup and get back on track. Your patience is appreciated! 🌟 #SmoothSailingAhead"
    ],
    "activation_code_controller" => [
        "error" => [
            "select_more_than_one_course" => "Please select only one course for single code type or switch to shared type",
            "select_less_than_two_course" => "Please select more than one course for shared code type or switch to single type.",
        ],
        "generate_codes_successfully" => "Activation codes generated successfully",
    ],
    "auth_controller" => [
        "error" => [
            "credentials_error" => "Oh no! 🙈 It seems like your passcode is playing hide and seek with our servers. Let's coax it out together. 🕵️‍♂️",
            "block_account_while_login" => "🚫 Uh-oh! Looks like you've logged in from a different device. For your security, we've temporarily blocked access."
        ],
        'register' => "Hooray! You're now part of the family. Let's make some magic happen!",
        "login" => "Welcome :user_name Back for more? Excellent choice! Let's make today even better than yesterday.",
        "logout" => "It's been a pleasure having you here. Take care and come back soon for more.",
    ],
    "category_controller" => [
        "create_category" => "🎉 New category created! 📦 Let's organize our content! #AdminPower",
        "update_category" => "🔄 Category updated! 🚀 Keeping things fresh and organized! #AdminMagic",
        "delete_category" => "🗑️ Category deleted! 🚫 Spring cleaning in progress! #AdminCleanup"
    ],
    "chapter_controller" => [
        "create" => "📚 New chapter added! 🌟 Let's expand our story universe! #ChapterCreation",
        "update" => "🔄 Chapter updated! 🔍 Enhancing our narrative journey! #ChapterUpdate",
        "delete" => "🗑️ Chapter deleted! 🚫 Keeping our story streamlined and focused! #ChapterDeletion",
        "visibility_switch" => "👁️‍🗨️ Chapter visibility switched! 🌈 Shining the spotlight on our latest narrative twists! "
    ],

    "choice_controller" => [
        "create" => "🎲 New choice added! 🌟 Expanding our options for exploration! #ChoiceCreation",
        "update" => "🔄 Choice updated! 🔍 Fine-tuning our decision-making journey! #ChoiceUpdate",
        "delete" => "🗑️ Choice deleted! 🚫 Keeping our options concise and relevant! #ChoiceDeletion",
        "visibility_switch" => "👁️‍🗨️ Choice visibility switched! 🌈 Shining the spotlight on our selected paths! #VisibilitySwitch",
        "make_choice_true" => "✅ Choice confirmed! 🎉 Embracing the chosen path with certainty! #ChoiceConfirmed"
    ],

    "course_controller" => [
        "error" => [
          "invisible_course" => "🔍 Course visibility update! 📚 The course you're trying to access is currently invisible. If you believe this is a mistake or have any questions, please reach out to the administrator for further assistance. #InvisibleCourse",
          "user_already_enrolled" => "⚠️ Enrollment Failed! ⚠️
You are already enrolled in the course :course_name Duplicate enrollments are not allowed. If you have any questions or concerns, please contact support for assistance. #EnrollmentConflict",
          "already_enrolled" => "🛑 Duplicate enrollment detected! 📚 The user :username is already enrolled in :course_name. Please review enrollment records to avoid duplications. #DuplicateEnrollmentAlert",
          "one_category_at_least" => "⚠️ Error: Course creation failed! ⚠️ To proceed, please provide at least one category for the new course. Categories are essential for organizing our educational content. Please try again with the necessary information. Thank you! #CategoryRequirementError",
          "one_teacher_at_least" => "⚠️ Error: Course creation failed! ⚠️ To proceed, please assign at least one teacher to the new course. Teachers are essential for guiding our students' learning journeys. Please try again with the necessary information. Thank you! #TeacherRequirementError",
          "wrong_match_course_with_code" => "⚠️ Activation Failed! ⚠️
The activation code you provided cannot be used to activate the requested course. Please ensure you have entered the correct code or contact support for assistance. Thank you for your understanding. #InvalidActivationCode",
        ],
        "create" => "📚 New course created! 🚀 The course :course_name has been successfully created. It's time to shape minds and inspire learning! #NewCourseCreated",
        "update" => "🔄 Course updated! 📚 The course :course_name has been successfully updated with the latest information. Let's keep enriching minds and empowering learners! #CourseUpdate",
        "delete" => "🧹 Course erased! 📝 Making space for fresh educational endeavors. #CourseErased",
        "cancel_enrolment" => "🚫 Enrollment canceled Successfully",
        "visibility_switch" => "👁️‍🗨️ Course visibility switched! 🌟 The visibility has been updated. Let's continue shaping our educational landscape! #VisibilitySwitch",
        "free_switch" => "💰 Free status updated! 💸 the course is now :status . We're committed to providing accessible education. #FreeStatusSwitch",
        "manual_enrolled_successfully" => "✅ Enrollment process completed successfully! 📚 :username has been successfully enrolled in :course_name. The course roster has been updated.",
        "enroll_successfully" => "✅ Enrollment Successful! 📚
Congratulations! You have been successfully enrolled in the course :course_name Get ready to embark on a journey of learning and discovery. If you have any questions or need assistance, feel free to reach out. Happy learning! #EnrollmentSuccess",

    ]

//    // Authentication messages
//    'create_new_user' => 'Welcome to the House Of Geniuses application, your account has been created successfully.',
//    'login' => 'Welcome back, :name! You have successfully logged in.',
//    'logout' => 'Goodbye, dear :name! You have successfully logged out.',
//
//    // Not found messages
//    'user_not_found' => 'Sorry, we couldn\'t find this account. Double-check and try again.',
//    'course_not_found' => 'Sorry, we couldn\'t find this training course in our system.',
//    'news_not_found' => 'This news was not found in our news list.',
//    'category_not_found' => 'This category was not found in the list of classifications.',
//    'activation_code_not_found' => 'Your training course activation code is incorrect.',
//
//    'activation_code_expired' => 'Unfortunately, your activation code has expired.',
//    'shared_activation_code_already_used_for_this_course' => 'You have already used your subscription activation code for this course.',
//    'sign_in_course_successfully' => 'Successfully signed in to the :course course. Best wishes for your success!',
//    'wrong_match_course_with_code' => 'This code is not valid for activating this course.',
//
//    'login_password_email_error' => 'There is an error in the email or password. Please verify and try again.',
//    'block_while_login_message' => 'Login with this account on this device is not possible. The account has been temporarily blocked. Contact the system administrator to resolve the issue.',
//    'account_blocked' => 'This account is blocked and cannot perform any operations. Contact the system administrator to resolve the issue and lift the block on the account.',
//
//    'course_invisible_sign_in' => 'You cannot join this training course now. Please try again later.',
//    'already_sign_in_course' => 'You have already registered for this training course.',
//
//    // Permission messages
//    'permission_denied' => 'Sorry, you cannot complete this operation as you do not have the appropriate permissions.',
//    'user_blocked' => ':block_state successfully applied.',
//    'block_word' => 'Block',
//    'unblock_word' => 'Unblock',
//    'delete_news' => 'The news has been successfully deleted from the news list.',
];
