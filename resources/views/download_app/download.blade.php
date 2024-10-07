<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="./logo2.jpg">
    <link rel="apple-touch-icon" href="./logo2.png" />
    <meta
        name="description"
        content="منصة بيت العباقرة للتعليم الالكتروني
كل ما يحتاجه طالب الاقتصاد في تطبيق واحد."
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@160..700&display=swap"
        rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap"
        rel="stylesheet"
    />
    @vite('resources/css/app.css')
    <title>House of geniuses</title>
</head>
<body>
    <main>
        <div class="main-content">
            <div class="w-[50%] flex items-center justify-center">
                <img
                    src="./logo2.jpg"
                    class="w-full md:max-w-[400px] rounded-[0px] md:rounded-[20px] "
                />
            </div>
            <div class="w-[50%] px-6  flex items-center justify-center">
                <div>
                    <h1 class="text-[30px] font-semibold mb-4">
                        بيت العباقرة: تعلم، ذاكر، وحقق النجاح بكل سهولة!
                    </h1>
                    <p class="text-[18px] mb-2 text-gray-400">
                        لقد وصلت إلى بيت العباقرة، حيث تتجلى المعرفة وتتحقق الإبداعات! استمتع بأفضل الكورسات التعليمية عبر الإنترنت مع مذكرات شاملة وحلول متقدمة تساعدك على التفوق وتحقيق أهدافك التعليمية بكل يسر وسهولة.
                    </p>
                    <h4 class="font-medium mb-3 text-[20px] max-w-[600px]">
                        منصة بيت العباقرة للتعليم الالكتروني
                        كل ما يحتاجه طالب الاقتصاد في تطبيق واحد
                    </h4>
                    <ul class="mb-6">
                        <li class="mb-4 text-[18px]">✅ خبرة في إنشاء وصناعة المحتوى التعليمي الالكتروني ساهمت في نجاح آلاف الطلاب في حوالي 40 كورس خلال 4 سنوات
                        </li>
                        <li class="mb-4 text-[18px]">
                            ✅ تصميم جذاب وسهل الاستخدام يقدم تجربة مستخدم ممتعة
                        </li>
                        <li class="mb-4 text-[18px]">
                            ✅ دعم تقني وخدمة عملاء سريعة
                        </li>
                    </ul>
                    <q class="text-red-400 text-[20px] block mb-4">جاهز للانطلاق! الآن على أندرويد وقريبًا على آيفون. كن من أوائل المستفيدين من بيت العباقرة، حيث تبدأ رحلتك نحو التفوق والنجاح.</q>
                    <div class="flex items-center justify-center">
                        <a href="{{ route('downloadApk') }}" class="custom-btn max-w-[200px] text-[20px] px-4 py-1 capitalize bg-[#FF8C00] rounded-full flex items-center justify-center">
                            download app
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer px-6 mt-5">
            <h1>للاستفسار زيارة مكتبة بيت العباقرة أو التواصل واتساب 0945364375</h1>
        </footer>
    </main>
</body>
</html>
