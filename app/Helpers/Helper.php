<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
use Webkul\AdminTheme\Repositories\Country\StateRepository;
use Webkul\Core\Enums\CurrencyPositionEnum;

class Helper
{
    public static function groupedStatesByCountries()
    {
        $collection = [];

        foreach (DB::table('country_states')->get() as $state) {
            $collection[$state->country_id][] = $state;
        }

        return $collection;
    }

    public function state_name($code): string
    {
        $state = app()->make(StateRepository::class)
            ->findOneByField('code', $code);

        return $state ? $state->default_name : '';
    }

    public function groupedAreasByStates()
    {
        $collection = [];

        foreach (DB::table('state_areas')->get() as $area) {
            $collection[$area->country_state_id][] = $area;
        }

        return $collection;
    }

    public static function groupedAreasByStatesCode()
    {
        $collection = [];

        foreach (DB::table('state_areas')->get() as $area) {
            $collection[$area->state_code][] = $area;
        }

        return $collection;
    }

    public function formatDate($date = null, $format = 'd-m-Y h:i:s A')
    {
        $channel = core()->getCurrentChannel();

        if (is_null($date)) {
            $date = Carbon::now();
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $date->setTimezone($channel->timezone);

        return $date->translatedFormat($format);
    }

    public function formatBasePrice(?float $price)
    {
        $currency = core()->getBaseCurrency();

        if (is_null($price)) {
            $price = 0;
        }

        $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);

        // استخدام تنسيق مخصص إذا تم تحديد موضع العملة
        if ($currency->currency_position) {

            $formatter->setSymbol(NumberFormatter::CURRENCY_SYMBOL, '');
            $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $currency->decimal ?? 2);

            $formattedCurrency = preg_replace('/^\s+|\s+$/u', '', $formatter->format($price));

            // تغيير فواصل المجموع إذا محددة
            if (! empty($currency->group_separator)) {
                $formattedCurrency = str_replace(
                    $formatter->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL),
                    $currency->group_separator,
                    $formattedCurrency
                );
            }

            // تغيير الفاصل العشري إذا محدد
            if (! empty($currency->decimal_separator) && ($currency->decimal ?? 2) > 0) {
                $formattedCurrency = str_replace(
                    $formatter->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL),
                    $currency->decimal_separator,
                    $formattedCurrency
                );
            }

            $symbol = ! empty($currency->symbol) ? $currency->symbol : $currency->code;

            return match ($currency->currency_position) {
                CurrencyPositionEnum::LEFT->value             => $symbol.$formattedCurrency,
                CurrencyPositionEnum::LEFT_WITH_SPACE->value  => $symbol.' '.$formattedCurrency,
                CurrencyPositionEnum::RIGHT->value            => $formattedCurrency.$symbol,
                CurrencyPositionEnum::RIGHT_WITH_SPACE->value => $formattedCurrency.' '.$symbol,
            };
        }
        if ($currency->symbol) {
            $formatter->setSymbol(NumberFormatter::CURRENCY_SYMBOL, $currency->symbol);
            return $formatter->formatCurrency($price,$currency->symbol);
        }

        return $formatter->formatCurrency($price, $currency->code);
    }
}
