import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import {
    InspectorControls,
    useBlockProps,
} from '@wordpress/block-editor';
import {
    PanelBody,
    SelectControl,
    ToggleControl,
    TextControl,
    Spinner,
    Placeholder
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import './style.css';
import './editor.css';

function decodeHTML(text) {
    const textArea = document.createElement('textarea');
    textArea.innerHTML = text;
    return textArea.value;
}

registerBlockType('custom-survey-plugin/survey-block', {
    title: __('Survey Block', 'custom-survey-plugin'),
    icon: 'chart-bar',
    category: 'widgets',
    attributes: {
        surveyId: {
            type: 'string',
            default: ''
        },
        showResponses: {
            type: 'boolean',
            default: false
        },
        showQuestions: {
            type: 'boolean',
            default: false
        },
        showParticipants: {
            type: 'boolean',
            default: false
        },
        filterQuestion: {
            type: 'string',
            default: ''
        },
        showFilterQuestion: {
            type: 'boolean',
            default: false
        },
        filterParticipant: {
            type: 'string',
            default: ''
        },
        showFilterParticipant: {
            type: 'boolean',
            default: false
        },
        filterAttribute: {
            type: 'string',
            default: ''
        },
        showFilterAttribute: {
            type: 'boolean',
            default: false
        },
        layout: {
            type: 'string',
            default: 'list' // default layout is list
        }
    },
    edit: ({ attributes, setAttributes }) => {
        const { surveyId, showResponses, showQuestions, showParticipants, filterQuestion, showFilterQuestion, filterParticipant, showFilterParticipant, filterAttribute, showFilterAttribute, layout } = attributes;

        const { surveys, isLoading, questions, responses, participants, attributes: surveyAttributes } = useSelect((select) => {
            const query = {
                per_page: -1,
                orderby: 'date',
                order: 'desc',
            };

            const surveys = select('core').getEntityRecords('postType', 'survey', query);
            const questions = surveyId ? select('core').getEntityRecords('postType', 'question', {
                ...query,
                meta_key: '_survey_id',
                meta_value: surveyId,
            }) : [];
            const responses = surveyId ? select('core').getEntityRecords('postType', 'response', {
                ...query,
                meta_key: '_survey_id',
                meta_value: surveyId,
            }) : [];
            const participants = surveyId ? select('core').getEntityRecords('postType', 'participant', {
                ...query,
                meta_key: '_survey_id',
                meta_value: surveyId,
            }) : [];
            const attributes = select('core').getEntityRecords('taxonomy', 'attribute', { parent: 0 });

            return {
                surveys: surveys,
                isLoading: !surveys,
                questions: questions,
                responses: responses,
                participants: participants,
                attributes: attributes
            };
        }, [surveyId]);

        const surveyOptions = surveys ? surveys.map((survey) => ({
            label: decodeHTML(survey.title.rendered),
            value: survey.id
        })) : [];

        const layoutOptions = [
            { label: __('List', 'custom-survey-plugin'), value: 'list' },
            { label: __('Grid', 'custom-survey-plugin'), value: 'grid' }
        ];

        const filteredQuestions = questions ? questions.filter(question => question.title.rendered.toLowerCase().includes(filterQuestion.toLowerCase())) : [];

        const attributeOptions = surveyAttributes ? surveyAttributes.map(attribute => ({
            label: decodeHTML(attribute.name),
            value: attribute.id
        })) : [];

        return (
            <div { ...useBlockProps() }>
                <InspectorControls>
                    <PanelBody title={ __('Survey Settings', 'custom-survey-plugin') }>
                        { isLoading ? (
                            <Spinner />
                        ) : (
                            <>
                                <SelectControl
                                    label={ __('Select a Survey', 'custom-survey-plugin') }
                                    value={ surveyId }
                                    options={ [ { label: __('Select a Survey', 'custom-survey-plugin'), value: '' }, ...surveyOptions ] }
                                    onChange={ (newSurveyId) => setAttributes({ surveyId: newSurveyId }) }
                                />
                                { surveyId && (
                                    <>
                                        <ToggleControl
                                            label={ __('Show Questions', 'custom-survey-plugin') }
                                            checked={ showQuestions }
                                            onChange={ (value) => setAttributes({ showQuestions: value }) }
                                        />
                                        <ToggleControl
                                            label={ __('Show Responses', 'custom-survey-plugin') }
                                            checked={ showResponses }
                                            onChange={ (value) => setAttributes({ showResponses: value }) }
                                        />
                                        <ToggleControl
                                            label={ __('Show Participants', 'custom-survey-plugin') }
                                            checked={ showParticipants }
                                            onChange={ (value) => setAttributes({ showParticipants: value }) }
                                        />
                                        <ToggleControl
                                            label={ __('Show Filter Question', 'custom-survey-plugin') }
                                            checked={ showFilterQuestion }
                                            onChange={ (value) => setAttributes({ showFilterQuestion: value }) }
                                        />
                                        <ToggleControl
                                            label={ __('Show Filter Participant', 'custom-survey-plugin') }
                                            checked={ showFilterParticipant }
                                            onChange={ (value) => setAttributes({ showFilterParticipant: value }) }
                                        />
                                        <ToggleControl
                                            label={ __('Show Filter Attribute', 'custom-survey-plugin') }
                                            checked={ showFilterAttribute }
                                            onChange={ (value) => setAttributes({ showFilterAttribute: value }) }
                                        />
                                        <TextControl
                                            label={ __('Filter Questions', 'custom-survey-plugin') }
                                            value={ filterQuestion }
                                            onChange={ (value) => setAttributes({ filterQuestion: value }) }
                                        />
                                        <SelectControl
                                            label={ __('Layout', 'custom-survey-plugin') }
                                            value={ layout }
                                            options={ layoutOptions }
                                            onChange={ (newLayout) => setAttributes({ layout: newLayout }) }
                                        />
                                    </>
                                )}
                            </>
                        ) }
                    </PanelBody>
                </InspectorControls>
                <Placeholder
                    icon="chart-bar"
                    label={ __('Survey Block Editor', 'custom-survey-plugin') }
                    instructions={ __('Select a survey from the settings.', 'custom-survey-plugin') }
                >
                    { surveyId && (
                        <>
                            { showQuestions && filteredQuestions && (
                                <div>
                                    <h3>{ __('Questions:', 'custom-survey-plugin') }</h3>
                                    <ul>
                                        { filteredQuestions.map(question => (
                                            <li key={ question.id }>{ decodeHTML(question.title.rendered) }</li>
                                        )) }
                                    </ul>
                                </div>
                            ) }
                            { showResponses && responses && (
                                <div>
                                    <h3>{ __('Responses:', 'custom-survey-plugin') }</h3>
                                    <ul>
                                        { responses.map(response => (
                                            <li key={ response.id }>{ decodeHTML(response.title.rendered) }</li>
                                        )) }
                                    </ul>
                                </div>
                            ) }
                            { showParticipants && participants && (
                                <div>
                                    <h3>{ __('Participants:', 'custom-survey-plugin') }</h3>
                                    <ul>
                                        { participants.map(participant => (
                                            <li key={ participant.id }>{ decodeHTML(participant.title.rendered) }</li>
                                        )) }
                                    </ul>
                                </div>
                            ) }
                            { showFilterAttribute && (
                                <div>
                                    <h3>{ __('Filter Attributes:', 'custom-survey-plugin') }</h3>
                                    <SelectControl
                                        value={ filterAttribute }
                                        options={ [ { label: __('Select an Attribute', 'custom-survey-plugin'), value: '' }, ...attributeOptions ] }
                                        onChange={ (newFilterAttribute) => setAttributes({ filterAttribute: newFilterAttribute }) }
                                    />
                                </div>
                            )}
                        </>
                    )}
                </Placeholder>
            </div>
        );
    },
    save: ({ attributes }) => {
        const { surveyId, showQuestions, showResponses, showParticipants, filterQuestion, showFilterQuestion, filterParticipant, showFilterParticipant, filterAttribute, showFilterAttribute, layout } = attributes;

        return (
            <div { ...useBlockProps.save() }>
                <div className={`survey-block layout-${layout}`}
                     data-survey-id={ surveyId }
                     data-show-questions={ showQuestions }
                     data-show-responses={ showResponses }
                     data-show-participants={ showParticipants }
                     data-show-filter-question={ showFilterQuestion }
                     data-filter-question={ filterQuestion }
                     data-show-filter-participant={ showFilterParticipant }
                     data-filter-participant={ filterParticipant }
                     data-show-filter-attribute={ showFilterAttribute }
                     data-filter-attribute={ filterAttribute }>
                </div>
            </div>
        );
    }
});
