// survey-frontend.js

document.addEventListener('DOMContentLoaded', () => {
    const questionHeaders = document.querySelectorAll('.question-header');
    const filterQuestionCheckboxes = document.querySelectorAll('.filter-question-checkbox');
    const filterParticipantSelect = document.getElementById('filter-participant');
    const filterAttributeSelect = document.getElementById('filter-attribute');

    questionHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const responsesContainer = header.nextElementSibling;
            if (responsesContainer.style.display === 'none' || responsesContainer.style.display === '') {
                responsesContainer.style.display = 'block';
            } else {
                responsesContainer.style.display = 'none';
            }
        });
    });

    const applyFilters = () => {
        const filterQuestionValues = Array.from(filterQuestionCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value.toLowerCase());
        const filterParticipantValue = filterParticipantSelect ? filterParticipantSelect.value.toLowerCase() : '';
        const filterAttributeValue = filterAttributeSelect ? filterAttributeSelect.value.toLowerCase() : '';

        questionHeaders.forEach(header => {
            const questionCard = header.parentElement;
            const questionId = questionCard.getAttribute('data-question-id');
            const responseCards = questionCard.querySelectorAll('.response-card');
            let showCard = false;

            if (filterQuestionValues.length > 0 && !filterQuestionValues.includes(questionId)) {
                questionCard.style.display = 'none';
                return;
            }

            responseCards.forEach(responseCard => {
                const participantId = responseCard.getAttribute('data-participant-id');
                const attributeIds = responseCard.getAttribute('data-attribute-ids').split(',');

                if ((filterParticipantValue && participantId !== filterParticipantValue) ||
                    (filterAttributeValue && !attributeIds.includes(filterAttributeValue))) {
                    responseCard.style.display = 'none';
                } else {
                    responseCard.style.display = '';
                    showCard = true;
                }
            });

            questionCard.style.display = showCard ? '' : 'none';
        });
    };

    if (filterQuestionCheckboxes) {
        filterQuestionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', applyFilters);
        });
    }

    if (filterParticipantSelect) {
        filterParticipantSelect.addEventListener('change', applyFilters);
    }

    if (filterAttributeSelect) {
        filterAttributeSelect.addEventListener('change', applyFilters);
    }
});
