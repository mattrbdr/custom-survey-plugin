document.addEventListener('DOMContentLoaded', () => {
    const questionHeaders = document.querySelectorAll('.question-header');
    const filterQuestionSelect = document.getElementById('filter-question');
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
        const filterQuestionValue = filterQuestionSelect ? filterQuestionSelect.value.toLowerCase() : '';
        const filterParticipantValue = filterParticipantSelect ? filterParticipantSelect.value.toLowerCase() : '';
        const filterAttributeValue = filterAttributeSelect ? filterAttributeSelect.value.toLowerCase() : '';

        questionHeaders.forEach(header => {
            const questionCard = header.parentElement;
            const questionId = questionCard.getAttribute('data-question-id');
            const responseCards = questionCard.querySelectorAll('.response-card');
            let showCard = false;

            if (filterQuestionValue && questionId !== filterQuestionValue) {
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

    if (filterQuestionSelect) {
        filterQuestionSelect.addEventListener('change', applyFilters);
    }

    if (filterParticipantSelect) {
        filterParticipantSelect.addEventListener('change', applyFilters);
    }

    if (filterAttributeSelect) {
        filterAttributeSelect.addEventListener('change', applyFilters);
    }

    document.querySelectorAll('.participant-name').forEach(participantName => {
        participantName.addEventListener('click', (e) => {
            const website = participantName.getAttribute('data-website');
            if (website) {
                window.open(website, '_blank');
            }
        });
    });
});
