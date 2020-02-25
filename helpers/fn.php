<?php

namespace lolita\functions;

use ReflectionFunction;
use function lolita\arr\rest;
use function lolita\arr\last;

/**
 * Call function with key => value args.
 *
 * @param string $name Function name.
 * @param array  $main_args Main parameters.
 *
 * @return Function
 */
function call( $name, $main_args = array() ) {
	$fn = new ReflectionFunction( $name );
	return function( $args ) use ( $fn, $main_args ) {
		$params = $fn->getParameters();
		if ( ! is_array( $args ) ) {
			$args = array(
				$params[0]->name => $args,
			);
		}
		$args    = array_merge( $args, $main_args );
		$fn_args = array_map(
			function( $el ) use ( $args ) {
				if ( array_key_exists( $el->name, $args ) ) {
					return $args[ $el->name ];
				}
				return null;
			},
			$params
		);

		return $fn->invokeArgs( $fn_args );
	};
}

/**
 * Run function or return initial value.
 *
 * @param  mixed $fn Function candidate.
 *
 * @return mixed
 */
function maybe_run( $fn ) {
	if ( is_callable( $fn ) ) {
		return $fn();
	}
	return $fn;
}

/**
 * Run first or second callback based on a condition.
 *
 * @param callable $condition Condition function.
 * @param callable $true_result If condition will return true we run this function.
 * @param callable $false_result If condition will return false we run this function.
 *
 * @return Chain
 */
function iif( $condition, $true_result, $false_result = null ) {
	if ( ! is_callable( $false_result ) ) {
		$false_result = function( $el ) {
			return '';
		};
	}
	return function( $el ) use ( $condition, $true_result, $false_result ) {
		if ( call_user_func( $condition, $el ) ) {
			return call_user_func( $true_result, $el );
		}
		return call_user_func( $false_result, $el );
	};
}

/**
 * Make a function from value.
 *
 * @param  mixed $val Value which will be returned.
 *
 * @return Function
 */
function from_val( $val ) {
	return function() use ( $val ) {
		return $val;
	};
}

/**
 * Curry parent function.
 *
 * @param callable $callable Some function.
 * @return callable
 */
function curry( $callable ) {
	if ( 0 === number_of_required_params( $callable ) ) {
		return make_function( $callable );
	}
	if ( 1 === f_fn_number_of_required_params( $callable ) ) {
		return curry_array_args( $callable, rest( func_get_args() ) );
	}

	return curry_array_args( $callable, rest( func_get_args() ) );
}


/**
 * How many parameters we should put.
 *
 * @param callable $callable Some function.
 * @return int
 */
function number_of_required_params( $callable ) {
	if ( is_array( $callable ) ) {
		$refl   = new ReflectionClass( $callable[0] );
		$method = $refl->getMethod( $callable[1] );
		return $method->getNumberOfRequiredParameters();
	}
	$refl = new ReflectionFunction( $callable );
	return $refl->getNumberOfRequiredParameters();
}

/**
 * If the callback is an array(instance, method),
 * it returns an equivalent function for PHP 5.3 compatibility.
 *
 * @param  callable $callable Some function.
 * @return callable
 */
function make_function( $callable ) {
	if ( is_array( $callable ) ) {
		return function() use ( $callable ) {
			return call_user_func_array( $callable, func_get_args() );
		};
	}
	return $callable;
}

/**
 * Curry array arguments.
 *
 * @param callable $callable Function to curry.
 * @param array    $args Function arguments.
 * @param bool     $left Is left?.
 * @return callable
 */
function curry_array_args( $callable, $args, $left = true ) {
	return function () use ( $callable, $args, $left ) {
		if ( is_fullfilled( $callable, $args ) ) {
			return execute( $callable, $args, $left );
		}
		$new_args = array_merge( $args, func_get_args() );
		if ( is_fullfilled( $callable, $new_args ) ) {
			return execute( $callable, $new_args, $left );
		}
		return curry_array_args( $callable, $new_args, $left );
	};
}

/**
 * Is fullfiled now?
 *
 * @param callable $callable Some function.
 * @param array    $args Arguments.
 * @return bool
 */
function is_fullfilled( $callable, $args ) {
	$args = array_filter(
		$args,
		function( $arg ) {
			return ! is_placeholder( $arg );
		}
	);
	return count( $args ) >= number_of_required_params( $callable );
}

/**
 * Checks if an argument is a placeholder.
 *
 * @param mixed $arg Is this Placeholder?.
 * @return boolean
 */
function is_placeholder( $arg ) {
	return $arg instanceof Placeholder;
}

/**
 * Execute callable.
 *
 * @throws Exception Argument Placeholder found on unexpected position !.
 * @param callable $callable Function to execute.
 * @param array    $args Function arguments.
 * @param bool     $left Is left?.
 * @return mixed
 */
function execute( $callable, $args, $left ) {
	if ( ! $left ) {
		$args = array_reverse( $args );
	}

	$placeholders = placeholder_positions( $args );
	if ( 0 < count( $placeholders ) ) {
		$n = number_of_required_params( $callable );
		$l = count( $placeholders ) - 1;
		if ( $n <= last( $placeholders[ $l ] ) ) {
			// This means that we have more placeholders then needed
			// I know that throwing exceptions is not really the
			// functional way, but this case should not happen.
			throw new Exception( 'Argument Placeholder found on unexpected position !' );
		}
		foreach ( $placeholders as $i ) {
			$args[ $i ] = $args[ $n ];
			array_splice( $args, $n, 1 );
		}
	}

	return call_user_func_array( $callable, $args );
}

/**
 * Gets an array of placeholders positions in the given arguments.
 *
 * @internal
 * @param  array $args Function arguments.
 * @return array
 */
function placeholder_positions( $args ) {
	return array_keys( array_filter( $args, 'is_placeholder' ) );
}

/**
 * Curry your function from right side.
 *
 * @param callable $callable Function to curry.
 * @return callable
 */
function curry_right( $callable ) {
	if ( number_of_required_params( $callable ) < 2 ) {
		return make_function( $callable );
	}
	return curry_array_args( $callable, rest( func_get_args() ), false );
}

/**
 * Curry right arguments.
 *
 * @param callable $callable Function to curry.
 * @param array    $args pass the arguments to be curried as an array.
 *
 * @return callable
 */
function curry_right_args( $callable, $args ) {
	return curry_array_args( $callable, $args, false );
}
