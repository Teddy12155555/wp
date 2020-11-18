<?php
/**
 * Displays Quiz Info Page Box
 *
 * Available Variables:
 *
 * @var object $quiz_view WpProQuiz_View_FrontQuiz instance.
 * @var object $quiz      WpProQuiz_Model_Quiz instance.
 * @var array  $shortcode_atts Array of shortcode attributes to create the Quiz.
 *
 * @since 3.2
 *
 * @package LearnDash\Quiz
 */
?>
<div class="wpProQuiz_infopage" style="display: none;">
	<h4><?php esc_html_e( 'Information', 'learndash' ); ?></h4>
	<?php
	if ( $quiz->isFormActivated() && $quiz->getFormShowPosition() == WpProQuiz_Model_Quiz::QUIZ_FORM_POSITION_END && ( ! $quiz->isShowReviewQuestion() || $quiz->isQuizSummaryHide() ) ) {
		$quiz_view->showFormBox();
	}
	?>
	<input type="button" name="endInfopage" value="
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
