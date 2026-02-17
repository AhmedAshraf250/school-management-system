<?php

return [

    /*
    |--------------------------------------------------------------------------
    | سطور لغة التحقق
    |--------------------------------------------------------------------------
    |
    | السطور التالية تحتوي على رسائل الخطأ الافتراضية المستخدمة في
    | فئة التحقق. بعض هذه القواعد لها نسخ متعددة مثل قواعد الحجم.
    | يمكنك تعديل كل رسالة حسب احتياجك.
    |
    */

    'accepted'             => 'يجب قبول حقل :attribute.',
    'active_url'           => 'حقل :attribute ليس رابطاً صحيحاً.',
    'after'                => 'يجب أن يكون حقل :attribute تاريخاً بعد :date.',
    'after_or_equal'       => 'يجب أن يكون حقل :attribute تاريخاً بعد أو يساوي :date.',
    'alpha'                => 'يجب أن يحتوي حقل :attribute على حروف فقط.',
    'alpha_dash'           => 'يجب أن يحتوي حقل :attribute على حروف وأرقام وشرطات وشرطات سفلية فقط.',
    'alpha_num'            => 'يجب أن يحتوي حقل :attribute على حروف وأرقام فقط.',
    'array'                => 'يجب أن يكون حقل :attribute مصفوفة.',
    'before'               => 'يجب أن يكون حقل :attribute تاريخاً قبل :date.',
    'before_or_equal'      => 'يجب أن يكون حقل :attribute تاريخاً قبل أو يساوي :date.',
    'between'              => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file'    => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string'  => 'يجب أن يكون عدد أحرف :attribute بين :min و :max.',
        'array'   => 'يجب أن يحتوي :attribute على عناصر بين :min و :max.',
    ],
    'boolean'              => 'يجب أن تكون قيمة حقل :attribute صح أو خطأ.',
    'confirmed'            => 'حقل تأكيد :attribute غير متطابق.',
    'date'                 => 'حقل :attribute ليس تاريخاً صحيحاً.',
    'date_equals'          => 'يجب أن يكون حقل :attribute تاريخاً مساوياً لـ :date.',
    'date_format'          => 'لا يتطابق حقل :attribute مع الصيغة :format.',
    'different'            => 'يجب أن يكون حقل :attribute وحقل :other مختلفين.',
    'digits'               => 'يجب أن يتكون حقل :attribute من :digits رقماً.',
    'digits_between'       => 'يجب أن يكون عدد أرقام :attribute بين :min و :max.',
    'dimensions'           => 'أبعاد الصورة في حقل :attribute غير صحيحة.',
    'distinct'             => 'حقل :attribute يحتوي على قيمة مكررة.',
    'email'                => 'يجب أن يكون حقل :attribute عنوان بريد إلكتروني صحيح.',
    'ends_with'            => 'يجب أن ينتهي حقل :attribute بأحد القيم التالية: :values.',
    'exists'               => 'القيمة المحددة في حقل :attribute غير صحيحة.',
    'file'                 => 'يجب أن يكون حقل :attribute ملفاً.',
    'filled'               => 'يجب أن يحتوي حقل :attribute على قيمة.',
    'gt'                   => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من :value كيلوبايت.',
        'string'  => 'يجب أن يكون عدد أحرف :attribute أكبر من :value.',
        'array'   => 'يجب أن يحتوي :attribute على أكثر من :value عناصر.',
    ],
    'gte'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من أو تساوي :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من أو يساوي :value كيلوبايت.',
        'string'  => 'يجب أن يكون عدد أحرف :attribute أكبر من أو يساوي :value.',
        'array'   => 'يجب أن يحتوي :attribute على :value عناصر أو أكثر.',
    ],
    'image'                => 'يجب أن يكون حقل :attribute صورة.',
    'in'                   => 'القيمة المحددة في حقل :attribute غير صحيحة.',
    'in_array'             => 'حقل :attribute غير موجود في :other.',
    'integer'              => 'يجب أن يكون حقل :attribute عدداً صحيحاً.',
    'ip'                   => 'يجب أن يكون حقل :attribute عنوان IP صحيحاً.',
    'ipv4'                 => 'يجب أن يكون حقل :attribute عنوان IPv4 صحيحاً.',
    'ipv6'                 => 'يجب أن يكون حقل :attribute عنوان IPv6 صحيحاً.',
    'json'                 => 'يجب أن يكون حقل :attribute نصاً بصيغة JSON صحيحة.',
    'lt'                   => [
        'numeric' => 'يجب أن تكون قيمة :attribute أصغر من :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أصغر من :value كيلوبايت.',
        'string'  => 'يجب أن يكون عدد أحرف :attribute أصغر من :value.',
        'array'   => 'يجب أن يحتوي :attribute على أقل من :value عناصر.',
    ],
    'lte'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أصغر من أو تساوي :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أصغر من أو يساوي :value كيلوبايت.',
        'string'  => 'يجب أن يكون عدد أحرف :attribute أصغر من أو يساوي :value.',
        'array'   => 'يجب ألا يحتوي :attribute على أكثر من :value عناصر.',
    ],
    'max'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أصغر من أو تساوي :max.',
        'file'    => 'يجب أن لا يتجاوز حجم الملف :attribute :max كيلوبايت.',
        'string'  => 'يجب أن لا يتجاوز عدد أحرف :attribute :max حرفاً.',
        'array'   => 'يجب أن لا يحتوي :attribute على أكثر من :max عناصر.',
    ],
    'mimes'                => 'يجب أن يكون حقل :attribute ملفاً من نوع: :values.',
    'mimetypes'            => 'يجب أن يكون حقل :attribute ملفاً من نوع: :values.',
    'min'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute على الأقل :min.',
        'file'    => 'يجب أن لا يقل حجم الملف :attribute عن :min كيلوبايت.',
        'string'  => 'يجب أن لا يقل عدد أحرف :attribute عن :min أحرف.',
        'array'   => 'يجب أن يحتوي :attribute على الأقل على :min عناصر.',
    ],
    'not_in'               => 'القيمة المحددة في حقل :attribute غير صحيحة.',
    'not_regex'            => 'صيغة حقل :attribute غير صحيحة.',
    'numeric'              => 'يجب أن يكون حقل :attribute رقماً.',
    'password'             => 'كلمة المرور غير صحيحة.',
    'present'              => 'يجب أن يكون حقل :attribute موجوداً.',
    'regex'                => 'صيغة حقل :attribute غير صحيحة.',
    'required'             => 'حقل :attribute مطلوب.',
    'required_if'          => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_unless'      => 'حقل :attribute مطلوب إلا إذا كان :other في :values.',
    'required_with'        => 'حقل :attribute مطلوب عندما يكون :values موجوداً.',
    'required_with_all'    => 'حقل :attribute مطلوب عندما تكون :values موجودة.',
    'required_without'     => 'حقل :attribute مطلوب عندما لا يكون :values موجوداً.',
    'required_without_all' => 'حقل :attribute مطلوب عندما لا يكون أي من :values موجوداً.',
    'same'                 => 'يجب أن يتطابق حقل :attribute مع حقل :other.',
    'size'                 => [
        'numeric' => 'يجب أن تكون قيمة :attribute :size.',
        'file'    => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت.',
        'string'  => 'يجب أن يكون عدد أحرف :attribute :size.',
        'array'   => 'يجب أن يحتوي :attribute على :size عناصر.',
    ],
    'starts_with'          => 'يجب أن يبدأ حقل :attribute بأحد القيم التالية: :values.',
    'string'               => 'يجب أن يكون حقل :attribute نصاً.',
    'timezone'             => 'يجب أن يكون حقل :attribute منطقة زمنية صحيحة.',
    'unique'               => 'قيمة حقل :attribute مستخدمة من قبل.',
    'uploaded'             => 'فشل تحميل الملف في حقل :attribute.',
    'url'                  => 'صيغة حقل :attribute غير صحيحة.',
    'uuid'                 => 'يجب أن يكون حقل :attribute معرفاً فريداً صحيحاً (UUID).',

    /*
    |--------------------------------------------------------------------------
    | سطور التحقق المخصصة
    |--------------------------------------------------------------------------
    |
    | يمكنك هنا تحديد رسائل تحقق مخصصة للحقول باستخدام الصيغة
    | "اسم_الحقل.اسم_القاعدة" لتسمية السطور، مما يتيح تحديد
    | رسالة مخصصة لقاعدة معينة في حقل معين بسرعة.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | أسماء حقول التحقق المخصصة
    |--------------------------------------------------------------------------
    |
    | السطور التالية تُستخدم لاستبدال placeholder الحقل
    | بشيء أكثر وضوحاً مثل "البريد الإلكتروني" بدلاً من "email".
    | هذا يجعل الرسائل أكثر تعبيراً وسهولة للقراءة.
    |
    */

    'attributes' => [],

];
