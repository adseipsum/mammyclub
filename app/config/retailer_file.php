<?php

$config['retailer_file']['default'] = array(
  'csv' => array(
    'identify_columns' => array(0 => 'bar_code'),
    'value_columns' => array(1),
  ),
  'worksheets' => array(
    'first|sheet' => array(
      'identify_columns' => array('A' => 'bar_code'),
      'value_columns' => array('B'),
      'cost_price_columns' => array('C'),
      'price_columns' => array('D'),
      'start_row' => 2,
    )
  )
);

$config['retailer_file']['standard_store'] = array(
	'csv' => array(
		'identify_columns' => array(0 => 'bar_code'),
		'value_columns' => array(1),
	),
	'worksheets' => array(
		'first|sheet' => array(
			'identify_columns' => array('B' => 'bar_code'),
			'value_columns' => array('C'),
			'start_row' => 2,
		)
	)
);

$config['retailer_file']['inventory_prices'] = array(
	'csv' => array(
		'identify_columns' => array(0 => 'bar_code'),
		'value_columns' => array(1),
	),
	'worksheets' => array(
		'first|sheet' => array(
			'identify_columns' => array('A' => 'bar_code'),
			'cost_price_columns' => array('C'),
			'price_columns' => array('D'),
			'start_row' => 2,
		)
	)
);

$config['retailer_file']['our-stock-zammler'] = array(
  'csv' => array(
    'identify_columns' => array(0 => 'bar_code'),
    'value_columns' => array(2),
  ),
);

$config['retailer_file']['Mamin Dom'] = array(
  'worksheets' => array(
    'TDSheet' => array(
      'identify_columns' => array('G' => 'bar_code'),
      'value_columns' => array('K'),
      'cost_price_columns' => array(),
      'price_columns' => array(),
      'start_row' => 2,
    )
  ),
);

$config['retailer_file']['Anita'] = array(
  'worksheets' => array(
    'TDSheet' => array(
      'identify_columns' => array('A' => 'bar_code'),
      'value_columns' => array('J'),
      'cost_price_columns' => array(),
      'price_columns' => array(),
      'start_row' => 5,
    )
  ),
);

$config['retailer_file']['Junimed'] = array(
  'worksheets' => array(
    'Остатки' => array(
      'identify_columns' => array('H' => 'bar_code'),
      'value_columns' => array('F'),
      'cost_price_columns' => array(),
      'price_columns' => array(),
      'start_row' => 3,
    )
  ),
);

$config['retailer_file']['Kiddisvit'] = array(
	'xml' => array(
		'map' => array('header', 'items', 'item'),
		'identify_columns' => array('item_barcode' => 'bar_code'),
		'value_columns' => array('item_availability' => 'value'),
		'cost_price_columns' => array('item_price1' => 'cost_price'),
    'price_columns' => array('item_price2' => 'price')
  ),
  'worksheets' => array(
    'Прайс-лист' => array(
      'identify_columns' => array('C' => 'bar_code'),
      'value_columns' => array('M'),
      'cost_price_columns' => array(),
      'price_columns' => array(),
      'start_row' => 13,
    )
  ),
);

$config['retailer_file']['Tiny Dnerp'] = array(
  'worksheets' => array(
    'TINYLOVE' => array(
      'identify_columns' => array('B' => 'bar_code'),
      'value_columns' => array('I'),
      'cost_price_columns' => array(),
      'price_columns' => array(),
      'start_row' => 4,
    ),
    'CYBEX' => array(
      'identify_columns' => array('B' => 'bar_code'),
      'value_columns' => array('H'),
      'cost_price_columns' => array(),
      'price_columns' => array(),
      'start_row' => 8,
    )
  ),
);

$config['retailer_file']['Kinderclub'] = array(
  'worksheets' => array(
    'TDSheet' => array(
      'identify_columns' => array('D' => 'bar_code'),
      'value_columns' => array('I'),
      'value_replace' => array(
        'Уточняйте' => '0',
        'Доступно' => '10'
      ),
      'cost_price_columns' => array(),
      'price_columns' => array(),
      'start_row' => 11,
    )
  ),
);

$config['retailer_file']['Chicco'] = array(
  'worksheets' => array(
    'Chicco' => array(
      'identify_columns' => array('B' => 'bar_code'),
      'value_columns' => array('I'),
      'value_replace' => array(
        'нет' => '0',
        'заканчивается' => '5',
        'есть' => '20'
      ),
      'cost_price_columns' => array(),
      'price_columns' => array(),
      'start_row' => 2,
    )
  ),
);

$config['retailer_file']['Ukrtoys'] = array(
  'worksheets' => array(
    'Sheet1' => array(
      'identify_columns' => array('C' => 'bar_code'),
      'value_columns' => array('I'),
      'value_replace' => array(
        '>10' => '20',
      ),
      'cost_price_columns' => array(),
      'price_columns' => array(),
      'start_row' => 12,
    )
  ),
);

$config['retailer_file']['ModernFamily'] = array(
  'files' => array(
    'Boon' => array(
      'worksheets' => array(
        'Лист1' => array(
          'identify_columns' => array('A' => 'product_code'),
          'value_columns' => array('F'),
          'value_replace' => array(
            'нет в наличии' => 0,
            'в наличии' => 10,
          ),
          'cost_price_columns' => array(),
          'price_columns' => array(),
          'start_row' => 9,
        )
      ),
    ),
    'Cosatto' => array(
      'worksheets' => array(
        'Sheet1' => array(
          'identify_columns' => array('A' => 'product_code'),
          'value_columns' => array('G'),
          'value_replace' => array(
            'нет в наличии' => 0,
            'снято с производства' => 0,
            'под заказ' => 0,
            'в наличии' => 10,
          ),
          'cost_price_columns' => array(),
          'price_columns' => array(),
          'start_row' => 12,
        )
      ),
    ),
    'SkipHop' => array(
      'worksheets' => array(
        'Лист1' => array(
          'identify_columns' => array('A' => 'product_code'),
          'value_columns' => array('F'),
          'value_replace' => array(
            'нет в наличии' => 0,
            'снято с производства' => 0,
            'под заказ' => 0,
            'в наличии' => 10,
          ),
          'cost_price_columns' => array(),
          'price_columns' => array(),
          'start_row' => 2,
        )
      ),
    )
  )

);
