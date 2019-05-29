<?php

namespace LolitaFramework\Data;

/**
 * Class for working with arrays
 */
class Arr {

  /**
   * Append item to array
   *
   * @usage lf::append([1, 2, 3], 4);
   *        >> [1, 2, 3, 4]
   *
   * @param array $array original array.
   * @param mixed $value new item or value to append.
   *
   * @return array
   */
  public static function append( $array = [], $value = null ) {
      $array[] = $value;
      return $array;
  }
}
