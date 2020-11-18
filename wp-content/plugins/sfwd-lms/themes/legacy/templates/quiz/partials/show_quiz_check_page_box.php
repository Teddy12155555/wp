<?php
/**
 * Displays Quiz Check Page Box
 *
 * Available Variables:
 *
 * @var object $quiz_view WpProQuiz_View_FrontQuiz instance.
 * @var object $quiz      WpProQuiz_Model_Quiz instance.
 * @var array  $shortcode_atts Array of shortcode attributes to create the Quiz.
 * @var int    $question_count Number of Question to display.
 *
 * @since 3.2
 *
 * @package LearnDash\Quiz
 */
?>
<div class="wpProQuiz_checkPage" style="display: none;">
	<h4 class="wpProQuiz_header">
	<?php
		echo wp_kses_post(
			SFWD_LMS::get_template(
				'learndash_quiz_messages',
				array(
					'quiz_post_id' => $quiz->getID(),
					'context'      => 'quiz_quiz_summary_header',
					// translators: Quiz Summary.
					'message'      => sprintf( esc_html_x( '%s Summary', 'Quiz Summary', 'learndash' ), LearnDash_Custom_Label::get_label( 'quiz' ) ),
				)
			)
		);
		?>
	</h4>
	<?php
		echo wp_kses_post(
			SFWD_LMS::get_template(
				'learndash_quiz_messages',
				array(
					'quiz_post_id' => $quiz->getID(),
					'context'      => 'quiz_checkbox_questions_complete_message',
					'message'      => '<p>' .
						sprintf(
							// translators: placeholders: quiz count completed, quiz count total.
							esc_html_x( '%1$s of %2$s questions completed', 'placeholders: quiz count completed, quiz count total', 'learndash' ),
							'<span>0</span>',
							$question_count
						) . '</p>',
					'placeholders' => array( '0', $question_count ),
				)
			)
		);
		?>
	<p><?php esc_html_e( 'Questions', 'learndash' ); ?>:</p>
	<div class="wpProQuiz_reviewSummary"></div>

	<?php
	if ( $quiz->isFormActivated() && $quiz->getFormShowPosition() == WpProQuiz_Model_Quiz::QUIZ_FORM_POSITION_END && ( $quiz->isShowReviewQuestion() && ! $quiz->isQuizSummaryHide() ) ) {
		?>
			<h4 class="wpProQuiz_header"><?php esc_html_e( 'Information', 'learndash' ); ?></h4>
			<?php
			$quiz_view->showFormBox();
	}
	?>

	<input type="button" name="endQuizSummary" value="
	<?php
	echo wp_kses_post(
		SFWD_LMS::get_template(
			'learndash_quiz_messages',
			array(
				'quiz_post_id' => $quiz->getID(),
				'context'      => 'quiz_finish_button_label',
				// translators: Finish Quiz Button Label.
				'message'      => sprintf( esc_html_x( 'Finish %s', 'Finish Quiz Button Label', 'learndash' ), LearnDash_Custom_Label::get_label( 'quiz' ) ),
			)
		)
	);
	?>
	" class="wpProQuiz_button" />
</div>
