<?php return array(
    'root' => array(
        'name' => 'wpfactory/wish-list-for-woocommerce',
        'pretty_version' => 'dev-master',
        'version' => 'dev-master',
        'reference' => 'd796241a18f9ed4273e692ec29b7eaad60fcc889',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'composer/installers' => array(
            'pretty_version' => 'v2.2.0',
            'version' => '2.2.0.0',
            'reference' => 'c29dc4b93137acb82734f672c37e029dfbd95b35',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/./installers',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'pablo-pacheco/wc-custom-settings-options' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '1f8c1a2a3bfb7a443649dd950362d039ceb1b50f',
            'type' => 'library',
            'install_path' => __DIR__ . '/../pablo-pacheco/wc-custom-settings-options',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'wpfactory/wish-list-for-woocommerce' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => 'd796241a18f9ed4273e692ec29b7eaad60fcc889',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
