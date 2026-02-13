<?php

return [
    [
        'key'    => 'sales.payment_methods.ba6ul_razorpay',
        'info' => 'Added Razorpay to the Bagisto',
        'name'   => 'Razorpay',
        'sort'   => 6,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'Checkout Display Title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'Checkout Description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'image',
                'title'         => 'Payment Method Logo',
                'type'          => 'image',
                'channel_based' => false,
                'locale_based'  => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ],
            [
                'name'          => 'key_id',
                'title'         => 'Razorpay Key ID',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
			[
                'name'          => 'secret',
                'title'         => 'Razorpay Key Secret',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ]
        ]
    ]
];