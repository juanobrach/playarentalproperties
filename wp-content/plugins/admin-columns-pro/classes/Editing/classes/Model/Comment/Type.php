<?php

namespace ACP\Editing\Model\Comment;

use ACP\Editing\Model;

class Type extends Model {

	public function save( $id, $value ) {
		$this->strategy->update( $id, array( 'comment_type' => $value ) );
	}

}