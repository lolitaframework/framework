<?php

namespace LolitaFramework\Data;

use \LolitaFramework\Data\Arr;

/**
 * Class for working with strings
 */
abstract class Str extends Arr {

	/**
	 * The cache of snake-cased words.
	 *
	 * @var array
	 */
	protected static $snake_cache = [];
	/**
	 * The cache of camel-cased words.
	 *
	 * @var array
	 */
	protected static $camel_cache = [];
	/**
	 * The cache of studly-cased words.
	 *
	 * @var array
	 */
	protected static $studly_cache = [];
	/**
	 * Transliterate a UTF-8 value to ASCII.
	 *
	 * @param  string $value input.
	 * @return string
	 */
	public static function ascii( $value ) {
		foreach ( static::chars_array() as $key => $val ) {
			$value = str_replace( $val, $key, $value );
		}
		return preg_replace( '/[^\x20-\x7E]/u', '', $value );
	}

	/**
	 * Convert a value to camel case.
	 *
	 * @param  string $value input.
	 * @return string
	 */
	public static function camel( $value ) {
		if ( isset( static::$camel_cache[ $value ] ) ) {
			return static::$camel_cache[ $value ];
		}
		static::$camel_cache[ $value ] = lcfirst( static::studly( $value ) );
		return static::$camel_cache[ $value ];
	}

	/**
	 * Generate a URL friendly "slug" from a given string.
	 *
	 * @param  string $title input.
	 * @param  string $separator default is -.
	 * @return string
	 */
	public static function slug( $title, $separator = '-' ) {
		$title = static::ascii( $title );
		// Convert all dashes/underscores into separator.
		$flip = '-' == $separator ? '_' : '-';
		$title = preg_replace( '![' . preg_quote( $flip ) . ']+!u', $separator, $title );
		// Remove all characters that are not the separator, letters, numbers, or whitespace.
		$title = preg_replace( '![^' . preg_quote( $separator ) . '\pL\pN\s]+!u', '', mb_strtolower( $title ) );
		// Replace all separator characters and whitespace by a single separator.
		$title = preg_replace( '![' . preg_quote( $separator ) . '\s]+!u', $separator, $title );
		return trim( $title, $separator );
	}


	/**
	 * Convert the given string to lower-case.
	 *
	 * @param  string $value upeer case string.
	 * @return string
	 */
	public static function lower( $value ) {
		return mb_strtolower( $value, 'UTF-8' );
	}


	/**
	 *  Replace all occurrences of the search string with the replacement string
	 *
	 * @param  string $subject The string or array being searched and replaced on, otherwise known as the haystack.
	 * @param  string $search The value being searched for, otherwise known as the needle. An array may be used to designate multiple needles.
	 * @param  string $replace The replacement value that replaces found search values. An array may be used to designate multiple replacements.
	 * @param  int    $count   If passed, this will be set to the number of replacements performed.
	 * @return string
	 */
	public static function str_replace( $subject, $search, $replace, $count = 0 ) {
		return str_replace( $search, $replace, $subject, $count );
	}

	/**
	 * Convert a value to studly caps case.
	 *
	 * @param  string $value input.
	 * @return string
	 */
	public static function studly( $value ) {
		$key = $value;
		if ( isset( static::$studly_cache[ $key ] ) ) {
			return static::$studly_cache[ $key ];
		}
		$value = ucwords( str_replace( array( '-', '_' ), ' ', $value ) );
		static::$studly_cache[ $key ] = str_replace( ' ', '', $value );
		return static::$studly_cache[ $key ];
	}

	/**
	 * Convert a string to snake case.
	 *
	 * @param  string $value input.
	 * @param  string $delimiter default _.
	 * @return string
	 */
	public static function snake( $value, $delimiter = '_' ) {
		$key = $value;
		if ( isset( static::$snake_cache[ $key ][ $delimiter ] ) ) {
			return static::$snake_cache[ $key ][ $delimiter ];
		}
		if ( ! ctype_lower( $value ) ) {
			$value = preg_replace( '/\s+/u', '', $value );
			$value = static::lower( preg_replace( '/(.)(?=[A-Z])/u', '$1' . $delimiter, $value ) );
		}
		static::$snake_cache[ $key ][ $delimiter ] = $value;
		return static::$snake_cache[ $key ][ $delimiter ];
	}

	/**
	 * Returns the replacements for the ascii method.
	 *
	 * @return array
	 */
	protected static function chars_array() {
		static $chars_array;
		if ( isset( $chars_array ) ) {
			return $chars_array;
		}
		$chars_array = array(
			'0'    => array( '°', '₀', '۰' ),
			'1'    => array( '¹', '₁', '۱' ),
			'2'    => array( '²', '₂', '۲' ),
			'3'    => array( '³', '₃', '۳' ),
			'4'    => array( '⁴', '₄', '۴', '٤' ),
			'5'    => array( '⁵', '₅', '۵', '٥' ),
			'6'    => array( '⁶', '₆', '۶', '٦' ),
			'7'    => array( '⁷', '₇', '۷' ),
			'8'    => array( '⁸', '₈', '۸' ),
			'9'    => array( '⁹', '₉', '۹' ),
			'a'    => array( 'à', 'á', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'ā', 'ą', 'å', 'α', 'ά', 'ἀ', 'ἁ', 'ἂ', 'ἃ', 'ἄ', 'ἅ', 'ἆ', 'ἇ', 'ᾀ', 'ᾁ', 'ᾂ', 'ᾃ', 'ᾄ', 'ᾅ', 'ᾆ', 'ᾇ', 'ὰ', 'ά', 'ᾰ', 'ᾱ', 'ᾲ', 'ᾳ', 'ᾴ', 'ᾶ', 'ᾷ', 'а', 'أ', 'အ', 'ာ', 'ါ', 'ǻ', 'ǎ', 'ª', 'ა', 'अ', 'ا' ),
			'b'    => array( 'б', 'β', 'Ъ', 'Ь', 'ب', 'ဗ', 'ბ' ),
			'c'    => array( 'ç', 'ć', 'č', 'ĉ', 'ċ' ),
			'd'    => array( 'ď', 'ð', 'đ', 'ƌ', 'ȡ', 'ɖ', 'ɗ', 'ᵭ', 'ᶁ', 'ᶑ', 'д', 'δ', 'د', 'ض', 'ဍ', 'ဒ', 'დ' ),
			'e'    => array( 'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'ë', 'ē', 'ę', 'ě', 'ĕ', 'ė', 'ε', 'έ', 'ἐ', 'ἑ', 'ἒ', 'ἓ', 'ἔ', 'ἕ', 'ὲ', 'έ', 'е', 'ё', 'э', 'є', 'ə', 'ဧ', 'ေ', 'ဲ', 'ე', 'ए', 'إ', 'ئ' ),
			'f'    => array( 'ф', 'φ', 'ف', 'ƒ', 'ფ' ),
			'g'    => array( 'ĝ', 'ğ', 'ġ', 'ģ', 'г', 'ґ', 'γ', 'ဂ', 'გ', 'گ' ),
			'h'    => array( 'ĥ', 'ħ', 'η', 'ή', 'ح', 'ه', 'ဟ', 'ှ', 'ჰ' ),
			'i'    => array( 'í', 'ì', 'ỉ', 'ĩ', 'ị', 'î', 'ï', 'ī', 'ĭ', 'į', 'ı', 'ι', 'ί', 'ϊ', 'ΐ', 'ἰ', 'ἱ', 'ἲ', 'ἳ', 'ἴ', 'ἵ', 'ἶ', 'ἷ', 'ὶ', 'ί', 'ῐ', 'ῑ', 'ῒ', 'ΐ', 'ῖ', 'ῗ', 'і', 'ї', 'и', 'ဣ', 'ိ', 'ီ', 'ည်', 'ǐ', 'ი', 'इ' ),
			'j'    => array( 'ĵ', 'ј', 'Ј', 'ჯ', 'ج' ),
			'k'    => array( 'ķ', 'ĸ', 'к', 'κ', 'Ķ', 'ق', 'ك', 'က', 'კ', 'ქ', 'ک' ),
			'l'    => array( 'ł', 'ľ', 'ĺ', 'ļ', 'ŀ', 'л', 'λ', 'ل', 'လ', 'ლ' ),
			'm'    => array( 'м', 'μ', 'م', 'မ', 'მ' ),
			'n'    => array( 'ñ', 'ń', 'ň', 'ņ', 'ŉ', 'ŋ', 'ν', 'н', 'ن', 'န', 'ნ' ),
			'o'    => array( 'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ø', 'ō', 'ő', 'ŏ', 'ο', 'ὀ', 'ὁ', 'ὂ', 'ὃ', 'ὄ', 'ὅ', 'ὸ', 'ό', 'о', 'و', 'θ', 'ို', 'ǒ', 'ǿ', 'º', 'ო', 'ओ' ),
			'p'    => array( 'п', 'π', 'ပ', 'პ', 'پ' ),
			'q'    => array( 'ყ' ),
			'r'    => array( 'ŕ', 'ř', 'ŗ', 'р', 'ρ', 'ر', 'რ' ),
			's'    => array( 'ś', 'š', 'ş', 'с', 'σ', 'ș', 'ς', 'س', 'ص', 'စ', 'ſ', 'ს' ),
			't'    => array( 'ť', 'ţ', 'т', 'τ', 'ț', 'ت', 'ط', 'ဋ', 'တ', 'ŧ', 'თ', 'ტ' ),
			'u'    => array( 'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'û', 'ū', 'ů', 'ű', 'ŭ', 'ų', 'µ', 'у', 'ဉ', 'ု', 'ူ', 'ǔ', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'უ', 'उ' ),
			'v'    => array( 'в', 'ვ', 'ϐ' ),
			'w'    => array( 'ŵ', 'ω', 'ώ', 'ဝ', 'ွ' ),
			'x'    => array( 'χ', 'ξ' ),
			'y'    => array( 'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'ÿ', 'ŷ', 'й', 'ы', 'υ', 'ϋ', 'ύ', 'ΰ', 'ي', 'ယ' ),
			'z'    => array( 'ź', 'ž', 'ż', 'з', 'ζ', 'ز', 'ဇ', 'ზ' ),
			'aa'   => array( 'ع', 'आ', 'آ' ),
			'ae'   => array( 'ä', 'æ', 'ǽ' ),
			'ai'   => array( 'ऐ' ),
			'at'   => array( '@' ),
			'ch'   => array( 'ч', 'ჩ', 'ჭ', 'چ' ),
			'dj'   => array( 'ђ', 'đ' ),
			'dz'   => array( 'џ', 'ძ' ),
			'ei'   => array( 'ऍ' ),
			'gh'   => array( 'غ', 'ღ' ),
			'ii'   => array( 'ई' ),
			'ij'   => array( 'ĳ' ),
			'kh'   => array( 'х', 'خ', 'ხ' ),
			'lj'   => array( 'љ' ),
			'nj'   => array( 'њ' ),
			'oe'   => array( 'ö', 'œ', 'ؤ' ),
			'oi'   => array( 'ऑ' ),
			'oii'  => array( 'ऒ' ),
			'ps'   => array( 'ψ' ),
			'sh'   => array( 'ш', 'შ', 'ش' ),
			'shch' => array( 'щ' ),
			'ss'   => array( 'ß' ),
			'sx'   => array( 'ŝ' ),
			'th'   => array( 'þ', 'ϑ', 'ث', 'ذ', 'ظ' ),
			'ts'   => array( 'ц', 'ც', 'წ' ),
			'ue'   => array( 'ü' ),
			'uu'   => array( 'ऊ' ),
			'ya'   => array( 'я' ),
			'yu'   => array( 'ю' ),
			'zh'   => array( 'ж', 'ჟ', 'ژ' ),
			'(c)'  => array( '©' ),
			'A'    => array( 'Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'Å', 'Ā', 'Ą', 'Α', 'Ά', 'Ἀ', 'Ἁ', 'Ἂ', 'Ἃ', 'Ἄ', 'Ἅ', 'Ἆ', 'Ἇ', 'ᾈ', 'ᾉ', 'ᾊ', 'ᾋ', 'ᾌ', 'ᾍ', 'ᾎ', 'ᾏ', 'Ᾰ', 'Ᾱ', 'Ὰ', 'Ά', 'ᾼ', 'А', 'Ǻ', 'Ǎ' ),
			'B'    => array( 'Б', 'Β', 'ब' ),
			'C'    => array( 'Ç', 'Ć', 'Č', 'Ĉ', 'Ċ' ),
			'D'    => array( 'Ď', 'Ð', 'Đ', 'Ɖ', 'Ɗ', 'Ƌ', 'ᴅ', 'ᴆ', 'Д', 'Δ' ),
			'E'    => array( 'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'Ë', 'Ē', 'Ę', 'Ě', 'Ĕ', 'Ė', 'Ε', 'Έ', 'Ἐ', 'Ἑ', 'Ἒ', 'Ἓ', 'Ἔ', 'Ἕ', 'Έ', 'Ὲ', 'Е', 'Ё', 'Э', 'Є', 'Ə' ),
			'F'    => array( 'Ф', 'Φ' ),
			'G'    => array( 'Ğ', 'Ġ', 'Ģ', 'Г', 'Ґ', 'Γ' ),
			'H'    => array( 'Η', 'Ή', 'Ħ' ),
			'I'    => array( 'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'Î', 'Ï', 'Ī', 'Ĭ', 'Į', 'İ', 'Ι', 'Ί', 'Ϊ', 'Ἰ', 'Ἱ', 'Ἳ', 'Ἴ', 'Ἵ', 'Ἶ', 'Ἷ', 'Ῐ', 'Ῑ', 'Ὶ', 'Ί', 'И', 'І', 'Ї', 'Ǐ', 'ϒ' ),
			'K'    => array( 'К', 'Κ' ),
			'L'    => array( 'Ĺ', 'Ł', 'Л', 'Λ', 'Ļ', 'Ľ', 'Ŀ', 'ल' ),
			'M'    => array( 'М', 'Μ' ),
			'N'    => array( 'Ń', 'Ñ', 'Ň', 'Ņ', 'Ŋ', 'Н', 'Ν' ),
			'O'    => array( 'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'Ø', 'Ō', 'Ő', 'Ŏ', 'Ο', 'Ό', 'Ὀ', 'Ὁ', 'Ὂ', 'Ὃ', 'Ὄ', 'Ὅ', 'Ὸ', 'Ό', 'О', 'Θ', 'Ө', 'Ǒ', 'Ǿ' ),
			'P'    => array( 'П', 'Π' ),
			'R'    => array( 'Ř', 'Ŕ', 'Р', 'Ρ', 'Ŗ' ),
			'S'    => array( 'Ş', 'Ŝ', 'Ș', 'Š', 'Ś', 'С', 'Σ' ),
			'T'    => array( 'Ť', 'Ţ', 'Ŧ', 'Ț', 'Т', 'Τ' ),
			'U'    => array( 'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'Û', 'Ū', 'Ů', 'Ű', 'Ŭ', 'Ų', 'У', 'Ǔ', 'Ǖ', 'Ǘ', 'Ǚ', 'Ǜ' ),
			'V'    => array( 'В' ),
			'W'    => array( 'Ω', 'Ώ', 'Ŵ' ),
			'X'    => array( 'Χ', 'Ξ' ),
			'Y'    => array( 'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ', 'Ÿ', 'Ῠ', 'Ῡ', 'Ὺ', 'Ύ', 'Ы', 'Й', 'Υ', 'Ϋ', 'Ŷ' ),
			'Z'    => array( 'Ź', 'Ž', 'Ż', 'З', 'Ζ' ),
			'AE'   => array( 'Ä', 'Æ', 'Ǽ' ),
			'CH'   => array( 'Ч' ),
			'DJ'   => array( 'Ђ' ),
			'DZ'   => array( 'Џ' ),
			'GX'   => array( 'Ĝ' ),
			'HX'   => array( 'Ĥ' ),
			'IJ'   => array( 'Ĳ' ),
			'JX'   => array( 'Ĵ' ),
			'KH'   => array( 'Х' ),
			'LJ'   => array( 'Љ' ),
			'NJ'   => array( 'Њ' ),
			'OE'   => array( 'Ö', 'Œ' ),
			'PS'   => array( 'Ψ' ),
			'SH'   => array( 'Ш' ),
			'SHCH' => array( 'Щ' ),
			'SS'   => array( 'ẞ' ),
			'TH'   => array( 'Þ' ),
			'TS'   => array( 'Ц' ),
			'UE'   => array( 'Ü' ),
			'YA'   => array( 'Я' ),
			'YU'   => array( 'Ю' ),
			'ZH'   => array( 'Ж' ),
			' '    => array(
				"\xC2\xA0",
				"\xE2\x80\x80",
				"\xE2\x80\x81",
				"\xE2\x80\x82",
				"\xE2\x80\x83",
				"\xE2\x80\x84",
				"\xE2\x80\x85",
				"\xE2\x80\x86",
				"\xE2\x80\x87",
				"\xE2\x80\x88",
				"\xE2\x80\x89",
				"\xE2\x80\x8A",
				"\xE2\x80\xAF",
				"\xE2\x81\x9F",
				"\xE3\x80\x80",
			),
		);
		return $chars_array;
	}
}
