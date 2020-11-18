<?php
/**
 * Displays Quiz Review Box
 *
 * Available Variables:
 *
 * @var object $quiz_view WpProQuiz_View_FrontQuiz instance.
 * @var object $quiz      WpProQuiz_Model_Quiz instance.
 * @var array  $shortcode_atts Array of shortcode attributes to create the Quiz.
 * @var int    $question_count Number of Question to display.
 * @since 3.2
 *
 * @package LearnDash\Quiz
 */
?>
<div class="wpProQuiz_reviewLegend">
	<ol>
		<li>
			<span class="wpProQuiz_reviewColor wpProQuiz_reviewQuestion_Target"></span>
			<span class="wpProQuiz_reviewText">
			<?php
				echo wp_kses_post(
					SFWD_LMS::get_template(
						'learndash_quiz_messages',
						array(
							'quiz_post_id' => $quiz->getID(),
							'context'      => 'quiz_quiz_current_message',
							'message'      => esc_html__( 'Current', 'learndash' ),
						)
					)
				);
				?>
				</span>
		</li>
		<?php
			$quizModus = (int) $quiz->getQuizModus();
			if ( 2 === $quizModus ) {
				$skipQuestionDisabled = $quiz->isSkipQuestionDisabled();
				if ( $skipQuestionDisabled ) {
					$review_label = esc_html__( 'Review', 'learndash' );
				} else {
					$review_label = esc_html__( 'Review / Skip', 'learndash' );
				}
				?>
				<li>
					<span class="wpProQuiz_reviewColor wpProQuiz_reviewColor_Review"></span>
					<span class="wpProQuiz_reviewText">
					<?php
						echo wp_kses_post(
							SFWD_LMS::get_template(
								'learndash_quiz_messages',
								array(
									'quiz_post_id' => $quiz->getID(),
									'context'      => 'quiz_quiz_review_message',
									'message'      => $review_label,
								)
							)
						);
					?>
					</span>
				</li>
				<li>
					<span class="wpProQuiz_reviewColor wpProQuiz_reviewColor_AnswerCorrect"></span>
					<span class="wpProQuiz_reviewText">
					<?php
						echo wp_kses_post(
							SFWD_LMS::get_template(
								'learndash_quiz_messages',
								array(
									'quiz_post_id' => $quiz->getID(),
									'context'      => 'quiz_quiz_answered_message',
									'message'      => esc_html__( 'Correct', 'learndash' ),
								)
							)
						);
					?>
					</span>
				</li>
				<li>
					<span class="wpProQuiz_reviewColor wpProQuiz_reviewColor_AnswerIncorrect"></span>
					<span class="wpProQuiz_reviewText">
					<?php
						echo wp_kses_post(
							SFWD_LMS::get_template(
								'learndash_quiz_messages',
								array(
									'quiz_post_id' => $quiz->getID(),
									'context'      => 'quiz_quiz_answered_message',
									'message'      => esc_html__( 'Incorrect', 'learndash' ),
								)
							)
						);
					?>
					</span>
				</li>
				<?php
			} else {
				?>
				<li>
					<span class="wpProQuiz_reviewColor wpProQuiz_reviewColor_Review"></span>
					<span class="wpProQuiz_reviewText">
					<?php
						echo wp_kses_post(
							SFWD_LMS::get_template(
								'learndash_quiz_messages',
								array(
									'quiz_post_id' => $quiz->getID(),
									'context'      => 'quiz_quiz_review_message',
									'message'      => esc_html__( 'Review', 'learndash' ),
								)
							)
						);
					?>
					</span>
				</li>	
				<li>
					<span class="wpProQuiz_reviewColor wpProQuiz_reviewColor_Answer"></span>
					<span class="wpProQuiz_reviewText">
					<?php
						echo wp_kses_post(
							SFWD_LMS::get_template(
								'learndash_quiz_messages',
								array(
									'quiz_post_id' => $quiz->getID(),
									'context'      => 'quiz_quiz_answered_message',
									'message'      => esc_html__( 'Answered', 'learndash' ),
								)
							)
						);
					?>
					</span>
				</li>
				<li>
					<span class="wpProQuiz_reviewColor wpProQuiz_reviewColor_AnswerIncorrect"></span>
					<span class="wpProQuiz_reviewText">
					<?php
						echo wp_kses_post(
							SFWD_LMS::get_template(
								'learndash_quiz_messages',
								array(
									'quiz_post_id' => $quiz->getID(),
									'context'      => 'quiz_quiz_answered_message',
									'message'      => esc_html__( 'Incorrect', 'learndash' ),
								)
							)
						);
					?>
					</span>
				</li>
				<?php
			}
		?>
	</ol>
	<div style="clear: both;"></div>
</div>