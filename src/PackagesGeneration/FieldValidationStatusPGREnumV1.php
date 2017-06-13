<?php
/**
 * File FieldValidationStatusPGRV1.php
 * Created at: 2017-06-12 21:20
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\DPDClient\PackagesGeneration;

final class FieldValidationStatusPGREnumV1
{
    const OK = 'OK';
    const UNKNOWN_ERROR = 'UNKNOWN_ERROR';
    const DB_ERROR = 'DB_ERROR';
    const DONT_MATCH_DICTIONARY = 'DONT_MATCH_DICTIONARY';
    const DONT_MATCH_PATTERN = 'DONT_MATCH_PATTERN';
    const VALUE_EMPTY = 'VALUE_EMPTY';
    const VALUE_ZERO = 'VALUE_ZERO';
    const VALUE_OUT_OF_RANGE = 'VALUE_OUT_OF_RANGE';
    const VALUE_INCORRECT = 'VALUE_INCORRECT';
    const UNKNOWN_RDB_ERROR = 'UNKNOWN_RDB_ERROR';
    const DUPLICATED_KEY = 'DUPLICATED_KEY';
}