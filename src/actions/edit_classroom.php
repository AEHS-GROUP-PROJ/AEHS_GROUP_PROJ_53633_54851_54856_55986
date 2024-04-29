<?php

// Access check

if ( !$_USER['is_admin'] )
{
	message ( 'Access denied', 3 );

	return;
}

// Input checks

if
(
	!isset ( $_POST['classroom'] ) || !str_fit ( '[1-9]\d{0,15}', $_POST['classroom'] ) ||
	!isset ( $_POST['title'] ) ||
	!isset ( $_POST['location'] ) ||
	!isset ( $_POST['capacity'] )
)
{
	message ( 'Payload missing', 3 );

	return;
}

if ( !str_len ( $_POST['title'] = str_wash ( $_POST['title'] ) ) )
{
	message ( 'Please provide classroom\'s title', 2 );

	return;
}

if ( !str_len ( $_POST['location'] = str_wash ( $_POST['location'] ) ) )
{
	message ( 'Please provide classroom\'s location', 2 );

	return;
}

if ( !str_fit ( '\d+', $_POST['capacity'] ) )
{
	message ( 'Please provide classroom\'s capacity', 2 );

	return;
}

if ( !intval ( $_POST['capacity'] ) )
{
	message ( 'Classroom has to accomodate at least one student', 2 );

	return;
}

// Checking if classroom already exists

if
(
	sql ( '
	SELECT 1 FROM `classrooms` WHERE
		`classrooms`.id<>'.$_POST['classroom'].' AND
		`classrooms`.title='.sql_escape ( $_POST['title'], 50 ), 1 )
)
{
	message ( 'Classroom with such title already exists', 2 );

	return;
}

// Updating the classroom record

if
(
	sql ( '
	UPDATE `classrooms` SET
		`classrooms`.title='.sql_escape ( $_POST['title'], 50 ).',
		`classrooms`.location='.sql_escape ( $_POST['location'], 50 ).',
		`classrooms`.capacity='.min ( intval ( $_POST['capacity'] ), 250 ).'
	WHERE `classrooms`.id='.$_POST['classroom'], 1 )
)
	message ( 'Classroom successfully updated', 1 );

route ( 'classrooms' );