<?php

namespace PHPSTORM_META {

    // Laravel helper functions for better IDE support

    override(\config(), map([
        '' => '@',
    ]));

    override(\env(), type(0));

    override(\config_path(), type('string'));
}
