<?php

class Job_Postings
{
	/**
	 * constructor
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}//end __construct

	/**
	 * enqueue scripts and styles
	 */
	public function admin_enqueue_scripts()
	{
		$version = 1;
// <!-- Latest compiled and minified CSS -->
// <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

		// adding twitter bootstrap

		wp_register_style( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css' );
		wp_enqueue_style( 'bootstrap' );

		wp_register_style( 'bootstrap-theme', '//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css' );
		wp_enqueue_style( 'bootstrap-theme' );

		wp_register_script( 'bootstrap-js', '//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js' );
		wp_enqueue_script( 'bootstrap-js' );
		
		// end adding bootstrap

		wp_register_style( 'job-postings-admin', plugins_url( 'css/job-postings.css', __FILE__ ), array(), $version );
		wp_enqueue_style( 'job-postings-admin' );

		wp_register_script( 'job-postings', plugins_url( 'js/job-postings.js', __FILE__ ), array(), $version, TRUE );
		wp_register_script( 'job-postings-behavior', plugins_url( 'js/job-postings-behavior.js', __FILE__ ), array(), $version, TRUE );

		wp_enqueue_script( 'job-postings' );
		wp_enqueue_script( 'job-postings-behavior' );

	}//end admin_enqueue_scripts

	/**
	 * register menus
	 */
	public function admin_menu()
	{
		$page_title = "Cerate Job Postings";
		$menu_title = "Job Postings";
		$capability = "read";
		$menu_slug = "view_page";

		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, null);
		add_submenu_page( $menu_slug, 'View Job Posting', 'View Job Posting', 'read', $menu_slug, array( $this, 'view_page' ) );
		add_submenu_page( $menu_slug, 'Create Job Posting', 'Create Job Posting', 'edit_pages', 'create-job-postings', array( $this, 'create_page' ) );
	}//end admin_menu

	/**
	 * render the creation of posts page
	 */
	public function create_page()
	{
		// register javascript and css
		/*
		wp_register_script( 'job-postings', plugins_url( 'js/job-postings.js', __FILE__ ), array(), $version, TRUE );
		wp_enqueue_script( 'job-postings' );

		wp_register_style( 'job-postings-admin', plugins_url( 'css/job-postings.css', __FILE__ ), array(), $version );
		wp_enqueue_style( 'job-postings-admin' );
		*/
		$message = null;

		// echo '<p>'.jp::getNewPost().'</p>';
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] )
		{
			$contact_args = new stdClass();
			
			$contact_args->name = $_POST['name'];
			$contact_args->email = $_POST['email'];
			$contact_args->phone = $_POST['phone'];
			$contact_args->job_desc = $_POST['job_desc'];

			if (!$contact_id = jp::contactExists($contact_args))
			{
				$contact = new contact($contact_args);
				$contact_id = $contact->save();
			}

			$post_args = new stdClass();

			$post_args->application_link = $_POST['app_link'];
			$post_args->category = $_POST['cat'];
			$post_args->contact_id = $contact_id;
			$post_args->date_expire = date('Y-m-d', strtotime("+". $_POST['time_length'] . " days"));
			$post_args->department = $_POST['dept'];
			$post_args->description = $_POST['job_description'];
			$post_args->job_title = $_POST['job_title'];
			$post_args->pay_rate = $_POST['pay_rate'];
			$post_args->title = $_POST['post_title'];

			$post = new post($post_args);
			$post->save();

			$message = "Post was successfully submitted";

		}//end if

		// display the template
		include_once __DIR__ . '/templates/create_post.php';

	}//end create_page

	/**
	 * render the view post page
	 */
	public function view_page()
	{

		// register javascript and css
		/*
		wp_register_script( 'job-postings', plugins_url( 'js/job-postings.js', __FILE__ ), array(), $version, TRUE );
		wp_enqueue_script( 'job-postings' );

		wp_register_style( 'job-postings-admin', plugins_url( 'css/job-postings.css', __FILE__ ), array(), $version );
		wp_enqueue_style( 'job-postings-admin' );
		 */

		$posts = new posts();

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] )
		{
			// not sure what we are doing here yet, maybe sorting.
		}//end if

		include_once __DIR__ . '/templates/view_post.php';
	}//end create_page

}//end class
