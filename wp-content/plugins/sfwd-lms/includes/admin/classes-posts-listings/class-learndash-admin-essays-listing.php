<?php
/**
 * LearnDash Quiz Essays (sfwd-essays) Posts Listing Class.
 *
 * @package LearnDash
 * @subpackage admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ( class_exists( 'Learndash_Admin_Posts_Listing' ) ) && ( ! class_exists( 'Learndash_Admin_Essays_Listing' ) ) ) {
	/**
	 * Class for LearnDash Essays Listing Pages.
	 */
	class Learndash_Admin_Essays_Listing extends Learndash_Admin_Posts_Listing {

		/**
		 * Public constructor for class
		 */
		public function __construct() {
			$this->post_type = learndash_get_post_type_slug( 'essay' );

			parent::__construct();
		}

		/**
		 * Called via the WordPress init action hook.
		 */
		public function listing_init() {
			$this->selectors = array(
				'author'      => array(
					'type'                     => 'user',
					'show_all_value'           => '',
					'show_all_label'           => esc_html__( 'All Authors', 'learndash' ),
					'selector_filter_function' => array( $this, 'selector_filter_for_author' ),
					'selector_value_function'  => array( $this, 'selector_value_for_author' ),
				),
				'group_id'    => array(
					'type'                     => 'post_type',
					'post_type'                => learndash_get_post_type_slug( 'group' ),
					'show_all_value'           => '',
					'show_all_label'           => sprintf(
						// translators: placeholder: Groups.
						esc_html_x( 'All %s', 'placeholder: Groups', 'learndash' ),
						LearnDash_Custom_Label::get_label( 'groups' )
					),
					'listing_query_function'   => array( $this, 'listing_filter_by_group' ),
					'selector_filter_function' => array( $this, 'selector_filter_for_group' ),
					'selector_value_function'  => array( $this, 'selector_value_for_group' ),
				),
				'course_id'   => array(
					'type'                     => 'post_type',
					'post_type'                => learndash_get_post_type_slug( 'course' ),
					'show_all_value'           => '',
					'show_all_label'           => sprintf(
						// translators: placeholder: Courses.
						esc_html_x( 'All %s', 'placeholder: Courses', 'learndash' ),
						LearnDash_Custom_Label::get_label( 'courses' )
					),
					'listing_query_function'   => array( $this, 'listing_filter_by_course' ),
					'selector_filter_function' => array( $this, 'selector_filter_for_course' ),
					'selector_value_function'  => array( $this, 'selector_value_for_course' ),
					'selector_filters'         => array( 'group_id' ),
				),
				'lesson_id'   => array(
					'type'                     => 'post_type',
					'post_type'                => learndash_get_post_type_slug( 'lesson' ),
					'show_all_value'           => '',
					'show_all_label'           => sprintf(
						// translators: placeholder: Lessons.
						esc_html_x( 'All %s', 'placeholder: Lessons', 'learndash' ),
						LearnDash_Custom_Label::get_label( 'lessons' )
					),
					'listing_query_function'   => array( $this, 'listing_filter_by_lesson' ),
					'selector_filter_function' => array( $this, 'selector_filter_for_lesson' ),
					'selector_value_function'  => array( $this, 'selector_value_integer' ),
					'selector_filters'         => array( 'course_id' ),
				),
				'topic_id'    => array(
					'type'                     => 'post_type',
					'post_type'                => learndash_get_post_type_slug( 'topic' ),
					'show_all_value'           => '',
					'show_all_label'           => sprintf(
						// translators: placeholder: Topics.
						esc_html_x( 'All %s', 'placeholder: Topics', 'learndash' ),
						LearnDash_Custom_Label::get_label( 'topics' )
					),
					'listing_query_function'   => array( $this, 'listing_filter_by_topic' ),
					'selector_filter_function' => array( $this, 'selector_filter_for_topic' ),
					'selector_value_function'  => array( $this, 'selector_value_integer' ),
					'selector_filters'         => array( 'course_id', 'lesson_id' ),
				),
				'quiz_id'     => array(
					'type'                     => 'post_type',
					'post_type'                => learndash_get_post_type_slug( 'quiz' ),
					'show_all_value'           => '',
					'show_all_label'           => sprintf(
						// translators: placeholder: Quizzes.
						esc_html_x( 'All %s', 'placeholder: Quizzes', 'learndash' ),
						LearnDash_Custom_Label::get_label( 'quizzes' )
					),
					'listing_query_function'   => array( $this, 'filter_by_essay_quiz' ),
					'selector_filter_function' => array( $this, 'selector_filter_for_essay_quiz' ),
					'selector_value_function'  => array( $this, 'selector_value_integer' ),
					'selector_filters'         => array( 'course_id', 'lesson_id', 'topic_id' ),
				),
				'question_id' => array(
					'type'                     => 'post_type',
					'post_type'                => learndash_get_post_type_slug( 'question' ),
					'show_all_value'           => '',
					'show_all_label'           => sprintf(
						// translators: placeholder: Questions.
						esc_html_x( 'All %s', 'placeholder: Questions', 'learndash' ),
						LearnDash_Custom_Label::get_label( 'questions' )
					),
					'display'                  => array( $this, 'show_essay_question_selector' ),
					'listing_query_function'   => array( $this, 'filter_by_essay_question' ),
					'selector_filter_function' => array( $this, 'selector_filter_for_essay_question' ),
					'selector_value_function'  => array( $this, 'selector_value_integer' ),
					'selector_filters'         => array( 'quiz_id' ),
				),
			);

			$this->columns = array(
				'title'           => array(
					'label' => esc_html__( 'Essay Question Title', 'learndash' ),
				),
				'author'          => array(
					'label' => esc_html__( 'Submitted By', 'learndash' ),
				),
				'approval_status' => array(
					'label'   => esc_html__( 'Status / Points', 'learndash' ),
					'after'   => 'author',
					'display' => array( $this, 'show_column_approval_status' ),
				),
				'quiz'            => array(
					'label'   => sprintf(
						// translators: Assigned Quiz Label.
						esc_html_x( 'Assigned %s', 'Assigned Quiz Label', 'learndash' ),
						LearnDash_Custom_Label::get_label( 'quiz' )
					),
					'after'   => 'approval_status',
					'display' => array( $this, 'show_column_essay_quiz' ),
				),
				'question'        => array(
					'label'   => sprintf(
						// translators: Assigned Question Label.
						esc_html_x( 'Assigned %s', 'Assigned Question Label', 'learndash' ),
						LearnDash_Custom_Label::get_label( 'question' )
					),
					'after'   => 'quiz',
					'display' => array( $this, 'show_column_essay_question' ),
				),
				'course'          => array(
					'label'    => sprintf(
						// translators: Assigned Course Label.
						esc_html_x( 'Assigned %s', 'Assigned Course Label', 'learndash' ),
						LearnDash_Custom_Label::get_label( 'course' )
					),
					'after'    => 'question',
					'display'  => array( $this, 'show_column_essay_course' ),
					'required' => false,
				),
				'lesson_topic'    => array(
					'label'   => sprintf(
						// translators: Placeholders: Lesson, Topic.
						esc_html_x( 'Assigned %1$s / %2$s', 'Placeholders: Lesson, Topic', 'learndash' ),
						LearnDash_Custom_Label::get_label( 'lesson' ),
						LearnDash_Custom_Label::get_label( 'topic' )
					),
					'after'   => 'course',
					'display' => array( $this, 'show_column_step_lesson_or_topic' ),
				),
			);

			parent::listing_init();
		}

		/**
		 * Call via the WordPress load sequence for admin pages.
		 */
		public function on_load_listing() {
			if ( $this->post_type_check() ) {

				parent::on_load_listing();

				add_action( 'admin_footer', array( $this, 'essay_bulk_actions' ), 30 );
				add_filter( 'post_row_actions', array( $this, 'essay_inline_actions' ), 30, 2 );
				add_filter( 'learndash_listing_table_query_vars_filter', array( $this, 'listing_table_query_vars_filter_essays' ), 30, 3 );

				//add_filter( 'views_edit-' . learndash_get_post_type_slug( 'essay' ), array( $this, 'edit_list_table_views' ), 10, 1 );

				$this->essay_bulk_actions_approve();
			}
		}

		/** This function is documented in includes/admin/class-learndash-admin-posts-listing.php */
		public function listing_table_query_vars_filter_essays( $q_vars, $post_type, $query ) {
			if ( ( learndash_is_group_leader_user( get_current_user_id() ) ) && ( 'advanced' !== learndash_get_group_leader_manage_users() ) ) {
				$gl_user_ids = learndash_get_groups_administrators_users( get_current_user_id() );
				if ( ! empty( $gl_user_ids ) ) {
					$q_vars['author__in'] = $gl_user_ids;
				} else {
					$q_vars['author__in'] = array( 0 );
				}
			}

			return $q_vars;
		}

				/**
		 * Filter the main query listing by the course_id
		 *
		 * @since 3.2.3
		 *
		 * @param  object $q_vars   Query vars used for the table listing
		 * @param  array  $selector Array of attributes used to display the filter selector.
		 *
		 * @return object $q_vars.
		 */
		protected function listing_filter_by_course( $q_vars, $selector = array() ) {
			if ( ( isset( $selector['selected'] ) ) && ( ! empty( $selector['selected'] ) ) ) {
				if ( ( isset( $selector['show_empty_value'] ) ) && ( $selector['show_empty_value'] === $selector['selected'] ) ) {
					if ( ! isset( $q_vars['meta_query'] ) ) {
						$q_vars['meta_query'] = array();
					}
					$q_vars['meta_query'][] = array(
						'relation' => 'OR',
						array(
							'key'     => 'course_id',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => 'course_id',
							'value'   => '0',
							'compare' => '=',
						),
					);
				} else {
					if ( ! isset( $q_vars['meta_query'] ) ) {
						$q_vars['meta_query'] = array();
					}

					$q_vars['meta_query'][] = array(
						'key'   => 'course_id',
						'value' => absint( $selector['selected'] ),
					);
				}
			}

			return $q_vars;
		}

		/**
		 * Filter the main query listing by the lesson_id
		 *
		 * @since 3.2.3
		 *
		 * @param  object $q_vars   Query vars used for the table listing.
		 * @param  array  $selector Array of attributes used to display the filter selector.
		 *
		 * @return object $q_vars.
		 */
		protected function listing_filter_by_lesson( $q_vars, $selector = array() ) {
			if ( ( isset( $selector['selected'] ) ) && ( ! empty( $selector['selected'] ) ) ) {
				if ( ( isset( $selector['show_empty_value'] ) ) && ( $selector['show_empty_value'] === $selector['selected'] ) ) {
					if ( ! isset( $q_vars['meta_query'] ) ) {
						$q_vars['meta_query'] = array();
					}
					$q_vars['meta_query'][] = array(
						'relation' => 'OR',
						array(
							'key'     => 'lesson_id',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => 'lesson_id',
							'value'   => '0',
							'compare' => '=',
						),
					);
				} else {
					if ( ! isset( $q_vars['meta_query'] ) ) {
						$q_vars['meta_query'] = array();
					}

					$lesson_ids      = array( absint( $selector['selected'] ) );
					$course_selector = $this->get_selector( 'course_id' );
					if ( ( $course_selector ) && ( isset( $course_selector['selected'] ) ) && ( ! empty( $course_selector['selected'] ) ) ) {
						$topics = learndash_get_topic_list( $selector['selected'], $course_selector['selected'] );
						if ( ! empty( $topics ) ) {
							$lesson_ids = array_merge( $lesson_ids, wp_list_pluck( $topics, 'ID' ) );
						}
					}

					$q_vars['meta_query'][] = array(
						'key'     => 'lesson_id',
						'compare' => 'IN',
						'value'   => $lesson_ids,
					);
				}
			}

			return $q_vars;
		}

		/**
		 * Filter the main query listing by the topic_id
		 *
		 * @since 3.2.3
		 *
		 * @param  object $q_vars   Query vars used for the table listing
		 * @param  array  $selector Array of attributes used to display the filter selector.
		 *
		 * @return object $q_vars.
		 */
		protected function listing_filter_by_topic( $q_vars, $selector = array() ) {
			if ( ( isset( $selector['selected'] ) ) && ( ! empty( $selector['selected'] ) ) ) {
				if ( 'yes' === LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Courses_Builder', 'shared_steps' ) ) {
					$steps_ids = learndash_course_get_steps_by_type( $selector['selected'], $this->post_type );
					if ( ! empty( $steps_ids ) ) {
						$q_vars['post__in'] = $steps_ids;
						$q_vars['orderby']  = 'post__in';
					}
				} else {

					if ( ! isset( $q_vars['meta_query'] ) ) {
						$q_vars['meta_query'] = array();
					} else {
						$lesson_item_found = false;
						foreach ( $q_vars['meta_query'] as $meta_idx => &$meta_item ) {
							if ( ( isset( $meta_item['key'] ) ) && ( 'lesson_id' === $meta_item['key'] ) ) {
								$lesson_item_found  = true;
								$meta_item['value'] = absint( $selector['selected'] );
								break;
							}
						}
						if ( ! $lesson_item_found ) {
							$q_vars['meta_query'][] = array(
								'key'   => 'lesson_id',
								'value' => absint( $selector['selected'] ),
							);
						}
					}
				}
			}

			return $q_vars;
		}

		/**
		 * Adds 'Approve' option next to certain selects on the Essay edit screen in the admin.
		 *
		 * Fires on `admin_footer` hook.
		 *
		 * @todo  check if needed, jQuery selector seems incorrect
		 *
		 * @since 2.3.0
		 */
		public function essay_bulk_actions() {
			global $post;

			if ( ( ! empty( $post->post_type ) ) && ( learndash_get_post_type_slug( 'essay' ) === $post->post_type ) ) {
				$approve_text = esc_html__( 'Approve', 'learndash' );
				?>
					<script type="text/javascript">
						jQuery( function() {
							jQuery('<option>').val('approve_essay').text('<?php echo esc_attr( $approve_text ); ?>').appendTo("select[name='action']");
							jQuery('<option>').val('approve_essay').text('<?php echo esc_attr( $approve_text ); ?>').appendTo("select[name='action2']");
						});
					</script>
				<?php
			}
		}

		/**
		 * Adds inline actions to Essay on post listing hover in the admin.
		 *
		 * Fires on `post_row_actions` hook.
		 *
		 * @since 2.1.0
		 *
		 * @param array   $actions An array of post actions.
		 * @param WP_Post $post    The `WP_Post` object.
		 *
		 * @return array $actions An array of post actions.
		 */
		public function essay_inline_actions( $actions, $post ) {
			if ( learndash_get_post_type_slug( 'essay' ) === $post->post_type ) {
				$upload = get_post_meta( $post->ID, 'upload', true );
				if ( ! empty( $upload ) ) {
					$actions['download_essay'] = '<a href="' . esc_url( $upload ) . '" target="_blank">' . esc_html__( 'Download', 'learndash' ) . '</a>';
				}
			}

			return $actions;
		}

		/**
		 * Handles the approval of the essay in bulk.
		 *
		 * Fires on `load-edit.php` hook.
		 *
		 * @since 2.3.0
		 */
		protected function essay_bulk_actions_approve() {
			if ( ( ! isset( $_REQUEST['ld-listing-nonce'] ) ) || ( empty( $_REQUEST['ld-listing-nonce'] ) ) || ( ! wp_verify_nonce( $_REQUEST['ld-listing-nonce'], get_called_class() ) ) ) {
				return;
			}

			if ( ( ! isset( $_REQUEST['post'] ) ) || ( empty( $_REQUEST['post'] ) ) || ( ! is_array( $_REQUEST['post'] ) ) ) {
				return;
			}

			if ( ( ! isset( $_REQUEST['post_type'] ) ) || ( learndash_get_post_type_slug( 'essay' ) !== $_REQUEST['post_type'] ) ) {
				return;
			}

			$action = '';
			if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] ) {
				$action = esc_attr( $_REQUEST['action'] );

			} elseif ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] ) {
				$action = esc_attr( $_REQUEST['action2'] );

			} elseif ( ( isset( $_REQUEST['ld_action'] ) ) && ( 'approve_essay' === $_REQUEST['ld_action'] ) ) {
				$action = 'approve_essay';
			}

			if ( 'approve_essay' === $action ) {

				if ( ( isset( $_REQUEST['post'] ) ) && ( ! empty( $_REQUEST['post'] ) ) ) {

					if ( ! is_array( $_REQUEST['post'] ) ) {
						$essays = array( $_REQUEST['post'] );
					} else {
						$essays = $_REQUEST['post'];
					}

					foreach ( $essays as $essay_id ) {

						if ( ( ! isset( $_REQUEST['essay_points'][ $essay_id ] ) ) || ( $_REQUEST['essay_points'][ $essay_id ] == '' ) ) {
							continue;
						}

						// get the new assigned points.
						$submitted_essay['points_awarded'] = intval( $_REQUEST['essay_points'][ $essay_id ] );

						$essay_post = get_post( $essay_id );
						if ( ( ! empty( $essay_post ) ) && ( $essay_post instanceof WP_Post ) && ( $essay_post->post_type === learndash_get_post_type_slug( 'essay' ) ) ) {

							if ( 'graded' !== $essay_post->post_status ) {
								$quiz_score_difference = 1;
							}

							// First we update the essat post with the new post_status
							$essay_post->post_status = 'graded';
							wp_update_post( $essay_post );

							$user_id     = $essay_post->post_author;
							$quiz_id     = get_post_meta( $essay_post->ID, 'quiz_id', true );
							$question_id = get_post_meta( $essay_post->ID, 'question_id', true );

							// Stole the following section ot code from learndash_save_essay_status_metabox_data();
							$submitted_essay_data = learndash_get_submitted_essay_data( $quiz_id, $question_id, $essay_post );

							if ( isset( $submitted_essay_data['points_awarded'] ) ) {
								$original_points_awarded = intval( $submitted_essay_data['points_awarded'] );
							} else {
								$original_points_awarded = 0;
							}

							$submitted_essay_data['status'] = 'graded';

							// get the new assigned points.
							$submitted_essay_data['points_awarded'] = intval( $_REQUEST['essay_points'][ $essay_id ] );

							/** This filter is documented in includes/quiz/ld-quiz-essays.php */
							$submitted_essay_data = apply_filters( 'learndash_essay_status_data', $submitted_essay_data );
							learndash_update_submitted_essay_data( $quiz_id, $question_id, $essay_post, $submitted_essay_data );

							if ( ! is_null( $original_points_awarded ) && ! is_null( $submitted_essay_data['points_awarded'] ) ) {
								if ( $submitted_essay_data['points_awarded'] > $original_points_awarded ) {
									$points_awarded_difference = intval( $submitted_essay_data['points_awarded'] ) - intval( $original_points_awarded );
								} else {
									$points_awarded_difference = ( intval( $original_points_awarded ) - intval( $submitted_essay_data['points_awarded'] ) ) * -1;
								}

								$updated_scoring_data = array(
									'updated_question_score' => $submitted_essay_data['points_awarded'],
									'points_awarded_difference' => $points_awarded_difference,
									'score_difference' => $quiz_score_difference,
								);

								/** This filter is documented in includes/quiz/ld-quiz-essays.php */
								$updated_scoring = apply_filters( 'learndash_updated_essay_scoring', $updated_scoring_data );
								learndash_update_quiz_data( $quiz_id, $question_id, $updated_scoring_data, $essay_post );

								/** This action is documented in includes/quiz/ld-quiz-essays.php */
								do_action( 'learndash_essay_all_quiz_data_updated', $quiz_id, $question_id, $updated_scoring_data, $essay_post );
							}
						}
					}
				}
			}
		}

		/**
		 * Show the Essay Approval Status.
		 *
		 * @since 3.2.3
		 *
		 * @param int $post_id Essay Post ID.
		 */
		protected function show_column_approval_status( $post_id = 0 ) {
			$post_id = absint( $post_id );
			if ( ! empty( $post_id ) ) {
				$essay              = get_post( $post_id );
				$post_status_object = get_post_status_object( $essay->post_status );
				if ( ( ! empty( $post_status_object ) ) && ( is_object( $post_status_object ) ) && ( property_exists( $post_status_object, 'label' ) ) ) {
					echo '<div class="ld-approval-status">' . sprintf(
						// translators: placeholder: Status.
						esc_html_x( 'Status: %s', 'placeholder: Status', 'learndash' ),
						esc_html( $post_status_object->label )
					) . '</div>';
				}

				$quiz_id     = get_post_meta( $post_id, 'quiz_id', true );
				$question_id = get_post_meta( $post_id, 'question_id', true );

				if ( ! empty( $quiz_id ) ) {
					$question_mapper = new WpProQuiz_Model_QuestionMapper();
					$question        = $question_mapper->fetchById( intval( $question_id ), null );
					if ( $question instanceof WpProQuiz_Model_Question ) {

						$submitted_essay_data = learndash_get_submitted_essay_data( $quiz_id, $question_id, $essay );

						echo '<div class="ld-approval-points">';
						$max_points = $question->getPoints();

						$current_points = 0;
						if ( isset( $submitted_essay_data['points_awarded'] ) ) {
							$current_points = intval( $submitted_essay_data['points_awarded'] );
						}

						if ( 'not_graded' === $essay->post_status ) {
							echo sprintf(
								// translators: placeholders: Points label, points input, maximum points.
								esc_html_x( '%1$s: %2$s / %3$d', 'placeholders: Points label, points input, maximum points', 'learndash' ),
								'<label class="learndash-listing-row-field-label" for="essay_points_' . $post_id . '">' . esc_html__( 'Points', 'learndash' ) . '</label>',
								'<input id="essay_points_' . $post_id . '" class="small-text" type="number" value="' . absint( $current_points ) . '" max="' . $max_points . '" min="0" step="1" name="essay_points[' . $post_id . ']" />',
								absint( $max_points )
							);
						} else {
							echo sprintf(
								// translators: placeholders: Points label, current points, maximum points.
								esc_html_x( '%1$s: %2$d / %3$d', 'placeholders: Points label, points input, maximum points', 'learndash' ),
								'<span class="learndash-listing-row-field-label">' . esc_html__( 'Points', 'learndash' ) . '</span>',
								absint( $current_points ),
								absint( $max_points )
							);
						}
						echo '</div>';
					}
				}

				if ( 'not_graded' === $essay->post_status ) {
					?>
					<div class="ld-approval-action">
					<button id="essay_approve_<?php echo absint( $post_id ); ?>" class="small essay_approve_single"><?php esc_html_e( 'approve', 'learndash' ); ?></button>
					</div>
					<?php
				}
			}
		}

		/**
		 * Show Course column for Essay.
		 *
		 * @since 3.2.3
		 *
		 * @param int $post_id The Essay post ID shown.
		 */
		protected function show_column_essay_course( $post_id = 0 ) {
			if ( ! empty( $post_id ) ) {
				$course_id = get_post_meta( $post_id, 'course_id', true );
				if ( ! empty( $course_id ) ) {
					$row_actions = array();

					$filter_url = add_query_arg( 'course_id', $course_id, $this->get_clean_filter_url() );
					echo '<a href="' . esc_url( $filter_url ) . '">' . get_the_title( $course_id ) . '</a>';
					$row_actions['ld-post-filter'] = '<a href="' . esc_url( $filter_url ) . '">' . esc_html__( 'filter', 'learndash' ) . '</a>';

					if ( current_user_can( 'edit_post', $course_id ) ) {
						$row_actions['ld-post-edit'] = '<a href="' . esc_url( get_edit_post_link( $course_id ) ) . '">' . esc_html__( 'edit', 'learndash' ) . '</a>';
					}
					if ( is_post_type_viewable( get_post_type( $course_id ) ) ) {
						$row_actions['ld-post-view'] = '<a href="' . esc_url( get_permalink( $course_id ) ) . '">' . esc_html__( 'view', 'learndash' ) . '</a>';
					}
					echo $this->list_table_row_actions( $row_actions );
				}
			}
		}

		/**
		 * Show Lesson column for Essay.
		 *
		 * @since 3.2.3
		 *
		 * @param int $post_id The Essay post ID shown.
		 */
		protected function show_column_essay_lesson( $post_id = 0 ) {
			$post_id = absint( $post_id );
			if ( ! empty( $post_id ) ) {
				$lesson_id = get_post_meta( $post_id, 'lesson_id', true );
				if ( ! empty( $lesson_id ) ) {
					$row_actions = array();

					$filter_url = add_query_arg( 'lesson_id', $lesson_id, $this->get_clean_filter_url() );
					echo '<a href="' . esc_url( $filter_url ) . '">' . get_the_title( $lesson_id ) . '</a>';
					$row_actions['ld-post-filter'] = '<a href="' . esc_url( $filter_url ) . '">' . esc_html__( 'filter', 'learndash' ) . '</a>';

					// $course_id = learndash_get_course_id( $lesson_id );
					$course_id = get_post_meta( $post_id, 'course_id', true );
					if ( current_user_can( 'edit_post', $lesson_id ) ) {
						$edit_url = get_edit_post_link( $lesson_id );

						if ( ! empty( $course_id ) ) {
							$edit_url = add_query_arg( 'course_id', $course_id, $edit_url );
						}
						$row_actions['ld-post-edit'] = '<a href="' . esc_url( $edit_url ) . '">' . esc_html__( 'edit', 'learndash' ) . '</a>';
					}

					if ( is_post_type_viewable( get_post_type( $lesson_id ) ) ) {
						if ( ! empty( $course_id ) ) {
							$view_url = learndash_get_step_permalink( $lesson_id, $course_id );
						} else {
							$view_url = get_permalink( $lesson_id );
						}
						$row_actions['ld-post-view'] = '<a href="' . esc_url( $view_url ) . '">' . esc_html__( 'view', 'learndash' ) . '</a>';
					}
					echo $this->list_table_row_actions( $row_actions );
				}
			}
		}

		/**
		 * Show Quiz column for Essay.
		 *
		 * @since 3.2.3
		 *
		 * @param int $post_id The Essay post ID shown.
		 */
		protected function show_column_essay_quiz( $post_id = 0 ) {
			$post_id = absint( $post_id );
			if ( ! empty( $post_id ) ) {
				$quiz_post_id = get_post_meta( $post_id, 'quiz_post_id', true );
				$quiz_post_id = absint( $quiz_post_id );
				if ( empty( $quiz_post_id ) ) {
					$user_quiz = learndash_get_user_quiz_entry_for_essay( $post_id );
					if ( ( isset( $user_quiz['quiz'] ) ) && ( ! empty( $user_quiz['quiz'] ) ) ) {
						$quiz_post_id = absint( $user_quiz['quiz'] );
						update_post_meta( $post_id, 'quiz_post_id', $quiz_post_id );
					}
				}

				if ( ! empty( $quiz_post_id ) ) {
					$filter_url = add_query_arg( 'quiz_id', $quiz_post_id, $this->get_clean_filter_url() );

					echo '<a href="' . esc_url( $filter_url ) . '" aria-label="' . esc_attr( $this->get_aria_label_for_post( $quiz_post_id, 'filter' ) ) . '">' . get_the_title( $quiz_post_id ) . '</a>';
					$row_actions['ld-post-filter'] = '<a href="' . esc_url( $filter_url ) . '" aria-label="' . esc_attr( $this->get_aria_label_for_post( $quiz_post_id, 'filter' ) ) . '">' . esc_html__( 'filter', 'learndash' ) . '</a>';

					// $course_id = learndash_get_course_id( $quiz_post_id );
					$course_id = get_post_meta( $post_id, 'course_id', true );
					if ( current_user_can( 'edit_post', $quiz_post_id ) ) {
						$edit_url = get_edit_post_link( $quiz_post_id );

						if ( ! empty( $course_id ) ) {
							$edit_url = add_query_arg( 'course_id', $course_id, $edit_url );
						}

						$row_actions['ld-post-edit'] = '<a href="' . esc_url( $edit_url ) . '" aria-label="' . esc_attr( $this->get_aria_label_for_post( $quiz_post_id, 'edit' ) ) . '">' . esc_html__( 'edit', 'learndash' ) . '</a>';
					}

					if ( is_post_type_viewable( get_post_type( $quiz_post_id ) ) ) {
						if ( ! empty( $course_id ) ) {
							$view_url = learndash_get_step_permalink( $quiz_post_id, $course_id );
						} else {
							$view_url = get_permalink( $quiz_post_id );
						}

						$row_actions['ld-post-view'] = '<a href="' . esc_url( $view_url ) . '" aria-label="' . esc_attr( $this->get_aria_label_for_post( $quiz_post_id, 'view' ) ) . '">' . esc_html__( 'view', 'learndash' ) . '</a>';
					}
					echo $this->list_table_row_actions( $row_actions );
				}
			}
		}

		/**
		 * Show Quiz column for Essay.
		 *
		 * @since 3.2.3
		 *
		 * @param int $post_id The Essay post ID shown.
		 */
		protected function show_column_essay_question( $post_id = 0 ) {
			$post_id = absint( $post_id );
			if ( ! empty( $post_id ) ) {
				$question_post_id = get_post_meta( $post_id, 'question_post_id', true );
				$question_post_id = absint( $question_post_id );
				if ( empty( $question_post_id ) ) {
					$question_pro_id = get_post_meta( $post_id, 'question_id', true );
					$question_pro_id = absint( $question_pro_id );
					if ( ! empty( $question_pro_id ) ) {
						$question_post_id = learndash_get_question_post_by_pro_id( $question_pro_id );
						$question_post_id = absint( $question_post_id );
						if ( ! empty( $question_post_id ) ) {
							update_post_meta( $post_id, 'question_post_id', $question_post_id );
						}
					}
				}

				if ( ! empty( $question_post_id ) ) {
					$filter_url = add_query_arg( 'question_id', $question_post_id, $this->get_clean_filter_url() );

					echo '<a href="' . esc_url( $filter_url ) . '" aria-label="' . esc_attr( $this->get_aria_label_for_post( $question_post_id, 'filter' ) ) . '">' . get_the_title( $question_post_id ) . '</a>';
					$row_actions['ld-post-filter'] = '<a href="' . esc_url( $filter_url ) . '" aria-label="' . esc_attr( $this->get_aria_label_for_post( $question_post_id, 'filter' ) ) . '">' . esc_html__( 'filter', 'learndash' ) . '</a>';

					$quiz_id = get_post_meta( $question_post_id, 'quiz_id', true );
					if ( current_user_can( 'edit_post', $question_post_id ) ) {
						$edit_url = get_edit_post_link( $question_post_id );

						if ( ! empty( $quiz_id ) ) {
							$edit_url = add_query_arg( 'quiz_id', $quiz_id, $edit_url );
						}

						$row_actions['ld-post-edit'] = '<a href="' . esc_url( $edit_url ) . '" aria-label="' . esc_attr( $this->get_aria_label_for_post( $question_post_id, 'edit' ) ) . '">' . esc_html__( 'edit', 'learndash' ) . '</a>';
					}

					if ( is_post_type_viewable( get_post_type( $question_post_id ) ) ) {
						if ( ! empty( $quiz_id ) ) {
							$view_url = learndash_get_step_permalink( $question_post_id, $quiz_id );

							$row_actions['ld-post-view'] = '<a href="' . esc_url( $view_url ) . '" aria-label="' . esc_attr( $this->get_aria_label_for_post( $question_post_id, 'view' ) ) . '">' . esc_html__( 'view', 'learndash' ) . '</a>';
						}
					}
					echo $this->list_table_row_actions( $row_actions );
				}
			}
		}

		/**
		 * This function fill filter the table listing items based on filters selected.
		 * Called via 'parse_query' filter from WP.
		 *
		 * @since 3.2.0
		 *
		 * @param  object $q_vars      Query vars used for the table listing
		 * @param  array  $selector Array of attributes used to display the filter selector.
		 * @return object $q_vars.
		 */
		protected function filter_by_approval_status( $q_vars, $selector ) {

			if ( ( isset( $selector['selected'] ) ) && ( ! empty( $selector['selected'] ) ) ) {
				if ( ! isset( $q_vars['meta_query'] ) ) {
					$q_vars['meta_query'] = array();
				}

				if ( 'approved' === $selector['selected'] ) {
					$q_vars['meta_query'][] = array(
						'key'   => 'approval_status',
						'value' => 1,
					);
				} elseif ( 'not_approved' === $selector['selected'] ) {
					$q_vars['meta_query'][] = array(
						'key'     => 'approval_status',
						'compare' => 'NOT EXISTS',
					);
				}
			}

			return $q_vars;
		}

		/**
		 * Filter the main query listing by the quiz_id.
		 *
		 * @since 3.2.3
		 *
		 * @param  object $q_vars      Query vars used for the table listing
		 * @param  array  $selector Array of attributes used to display the filter selector.
		 * @return object $q_vars.
		 */
		protected function filter_by_essay_quiz( $q_vars, $selector = array() ) {
			if ( ( isset( $selector['selected'] ) ) && ( ! empty( $selector['selected'] ) ) ) {
				$quiz_pro_id = get_post_meta( absint( $selector['selected'] ), 'quiz_pro_id', true );
				if ( ! empty( $quiz_pro_id ) ) {
					if ( ! isset( $q_vars['meta_query'] ) ) {
						$q_vars['meta_query'] = array();
					}

					$q_vars['meta_query'][] = array(
						'key'   => 'quiz_id',
						'value' => absint( $quiz_pro_id ),
					);
				}
			}

			return $q_vars;
		}

		/**
		 * Filter the main query listing by the question_id.
		 *
		 * @since 3.2.3
		 *
		 * @param  object $q_vars      Query vars used for the table listing
		 * @param  array  $selector Array of attributes used to display the filter selector.
		 * @return object $q_vars.
		 */
		protected function filter_by_essay_question( $q_vars = array(), $selector = array() ) {
			if ( ( isset( $selector['selected'] ) ) && ( ! empty( $selector['selected'] ) ) ) {
				$question_pro_id = get_post_meta( $selector['selected'], 'question_pro_id', true );
				$question_pro_id = absint( $question_pro_id );
				if ( ! empty( $question_pro_id ) ) {
					if ( ! isset( $q_vars['meta_query'] ) ) {
						$q_vars['meta_query'] = array();
					}

					$q_vars['meta_query'][] = array(
						'key'   => 'question_id',
						'value' => $question_pro_id,
					);
				}
			}

			return $q_vars;
		}

		/**
		 * Filter for Essay Quiz Selector
		 *
		 * @since 3.2.3
		 *
		 * @param  object $q_vars      Query vars used for the table listing
		 * @param  array  $selector Array of attributes used to display the filter selector.
		 * @return object $q_vars.
		 */
		protected function selector_filter_for_essay_quiz( $q_vars = array(), $selector = array() ) {
			global $sfwd_lms;

			if ( ( learndash_is_group_leader_user( get_current_user_id() ) ) && ( 'advanced' !== learndash_get_group_leader_manage_courses() ) ) {
				$course_id = 0;
				$lesson_id = 0;

				$course_selector = $this->get_selector( 'course_id' );
				if ( ( $course_selector ) && ( isset( $course_selector['selected'] ) ) && ( ! empty( $course_selector['selected'] ) ) ) {
					$course_id = absint( $course_selector['selected'] );
				}

				if ( empty( $course_id ) ) {
					$q_vars['post__in'] = array( 0 );
				} else {

					$lesson_selector = $this->get_selector( 'lesson_id' );
					if ( ( $lesson_selector ) && ( isset( $lesson_selector['selected'] ) ) && ( ! empty( $lesson_selector['selected'] ) ) ) {
						$lesson_id = absint( $lesson_selector['selected'] );
					}

					$quiz_items = $sfwd_lms->select_a_quiz( $course_id, $lesson_id );
					if ( ! empty( $quiz_items ) ) {
						$q_vars['post__in'] = array_keys( $quiz_items );
					} else {
						$q_vars['post__in'] = array( 0 );
					}
				}
			}

			return $q_vars;
		}

		/**
		 * Filter for Essay Question Selector
		 *
		 * @since 3.2.3
		 *
		 * @param  object $q_vars      Query vars used for the table listing
		 * @param  array  $selector Array of attributes used to display the filter selector.
		 * @return object $q_vars.
		 */
		protected function selector_filter_for_essay_question( $q_vars = array(), $selector = array() ) {
			global $sfwd_lms;

			$quiz_id = 0;

			$quiz_selector = $this->get_selector( 'quiz_id' );
			if ( ( $quiz_selector ) && ( isset( $quiz_selector['selected'] ) ) && ( ! empty( $quiz_selector['selected'] ) ) ) {
				$quiz_id = absint( $quiz_selector['selected'] );
			}

			if ( empty( $quiz_id ) ) {
				$q_vars['post__in'] = array( 0 );
			} else {
				$questions_ids = learndash_get_quiz_questions( $quiz_id );
				if ( ! empty( $questions_ids ) ) {
					$q_vars['post__in'] = array_keys( $questions_ids );
				} else {
					$q_vars['post__in'] = array( 0 );
				}
			}

			return $q_vars;
		}

		/**
		 * Show the Essay Question Selector.
		 *
		 * @since 3.2.3
		 *
		 * @param  array $selector Array of attributes used to display the filter selector.
		 */
		protected function show_essay_question_selector( $selector = array() ) {
			$this->show_selector_start( $selector );
			$this->show_selector_all_option( $selector );
			$this->show_selector_empty_option( $selector );

			$selector_options = array();
			$quiz_selector    = $this->get_selector( 'quiz_id' );
			if ( ( $quiz_selector ) && ( isset( $quiz_selector['selected'] ) ) && ( ! empty( $quiz_selector['selected'] ) ) ) {
				$quiz_questions = learndash_get_quiz_questions( $quiz_selector['selected'] );
				if ( ! empty( $quiz_questions ) ) {
					foreach ( $quiz_questions as $question_post_id => $question_pro_id ) {
						$selector_options[ $question_post_id ] = get_the_title( $question_post_id );
					}
				}
			}

			if ( ( isset( $selector['selected'] ) ) && ( ! empty( $selector['selected'] ) ) ) {
				$question_mapper = new WpProQuiz_Model_QuestionMapper();
				$question        = $question_mapper->fetchById( absint( $selector['selected'] ), null );
				if ( is_a( $question, 'WpProQuiz_Model_Question' ) ) {
					$selector_options[ $selector['selected'] ] = $question->getTitle();
				}
			}

			if ( ! empty( $selector_options ) ) {
				foreach ( $selector_options as $question_id => $question_title ) {
					echo '<option value="' . absint( $question_id ) . '" ' . selected( absint( $question_id ), absint( $selector['selected'] ), false ) . '>' . apply_filters( 'the_title', $question_title, $question_id ) . '</option>';
				}
			}

			$this->show_selector_end( $selector );
		}

		/**
		 * Hides the list table views for non admin users.
		 *
		 * Fires on `views_edit-sfwd-essays` and `views_edit-sfwd-assignment` hook.
		 *
		 * @param array $views Optional. An array of available list table views. Default empty array.
		 */
		public function edit_list_table_views( $views = array() ) {
			if ( ! learndash_is_admin_user() ) {
				$views = array();
			}

			return $views;
		}

		// End of functions.
	}
}
new Learndash_Admin_Essays_Listing();
