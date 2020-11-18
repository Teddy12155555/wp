<?php
/**
 * Displays Quiz Review Box
 *
 * Available Variables:
 *
 * @var object $quiz_view WpProQuiz_View_FrontQuiz instance.
 * @var object $quiz      WpProQuiz_Model_Quiz instance.
 * @var array  $shortcode_atts Array of shortcode attributes to create the Quiz.
 * @since 3.2
 *
 * @package LearnDash\Quiz
 */
?>
<div class="wpProQuiz_reviewButtons">
	<?php if ( $quiz->getQuizModus() != WpProQuiz_Model_Quiz::QUIZ_MODUS_SINGLE ) { ?>
		<input type="button" name="review" value="
		<?php
		echo wp_kses_post(
			SFWD_LMS::get_template(
				'learndash_quiz_messages',
				array(
					'quiz_post_id' => $quiz->getID(),
					'context'      => 'quiz_review_question_button_label',
					'message'      => esc_html__( 'Review question', 'learndash' ),
				)
			)
		);
		?>
		" class="wpProQuiz_button2" style="float: left; display: block;">
		<?php if ( ! $quiz->isQuizSummaryHide() ) { ?>
			<input type="button" name="quizSummary" value="
			<?php
			echo wp_kses_post(
				SFWD_LMS::get_template(
					'learndash_quiz_messages',
					array(
						'quiz_post_id' => $quiz->getID(),
						'context'      => 'quiz_quiz_summary_button_label',
						'message'      => sprintf(
							// translators: Quiz Summary.
							esc_html_x( '%s Summary', 'Quiz Summary', 'learndash' ),
							LearnDash_Custom_Label::get_label( 'quiz' )
						),
					)
				)
			);
			?>
			" class="wpProQuiz_button2" style="float: right;">
		<?php } ?>
		<div style="clear: both;"></div>
	<?php } ?>
</div>