<?php

use \Log;

class Beanstalk_Task {
	
	protected $max_time = 10;
	protected $pheanstalk = false;

	public function run() {
		
		Bundle::start( 'pheanstalk' );
		$begin_time = time();
		$this->pheanstalk = Pheanstalk::connection();
		
		$tubes = $this->pheanstalk->listTubes();

		foreach( $tubes as $tube ) {
			if( $tube == 'default' ) {
				continue;
			}
			while( true ) {
				if( ( time() - $begin_time)  > $this->max_time ) {
					break;
				}
				try { 
					if ( $this->process( $tube ) === false ) {
						break;
					}
				} catch( Exception $e ) {
					continue;
				}
			}
		}
	}
	
	
	public function process( $tube ) {
		
		$job = $this->pheanstalk->watch( $tube )->ignore( 'default' )->reserve( 1 );
		if( !is_object( $job ) ) {
			return false;
		}
		$serialized_job = $job->getData();
		if( $serialized_job ) {

			$data = unserialize( $serialized_job );
			if( $data == false ) {
				Log::info( $serialized_job );
				$this->pheanstalk->delete( $job );
				return true;
			}

			/* Specific code depending on the tube:
			*/
			switch( $tube ) {
				
				case 'example-tube':
					$this->exampletube( $data );
				break;
				
				default:
					return true;
			}
			$this->pheanstalk->delete( $job );
		}
		return true;
				
	}
	
	
	public function exampletube( $data ) {
			
		// Run the background job for the tube 'example-tube' here
		return true;
	}
	
}





				