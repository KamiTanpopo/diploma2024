document.addEventListener('DOMContentLoaded', function() {
    addEventListenersToSpecializationButtons();
    addContactButtonHandlers();
});

function addEventListenersToSpecializationButtons() {
    document.querySelectorAll('.specialization__button').forEach(button => {
        button.addEventListener('click', () => {
            // Remove the 'selected' class from any currently selected buttons
            document.querySelector('.specialization__button.selected')?.classList.remove('selected');
            button.classList.add('selected');

            // Get the ID and name of the selected specialization
            const specializationId = button.id.split('-')[1];
            const specializationName = button.textContent.trim();

            // Fetch data from the server using the specialization ID
            fetch(`/logic/fetch_by_specialty.php?specialization_id=${specializationId}`)
                .then(response => response.json())
                .then(data => {
                    // Clear the existing content in specialists__container
                    const container = document.querySelector('.specialists__container');
                    container.innerHTML = '';
                    
                    // Check if there are specialists
                    if (data.specialists && data.specialists.length > 0) {
                        // Create and append the cards
                        data.specialists.forEach(specialist => {
                            const specialistCard = document.createElement('div');
                            specialistCard.classList.add('specialist__card');

                            // Create specialist specializations, highlighting the selected one
                            const specializations = specialist.specializations.map(spec => 
                                `<div class="specialization ${spec === specializationName ? 'highlighted' : ''}"><h5>${spec}</h5></div>`).join('');

                            specialistCard.innerHTML = `
                                <div class="specialist__info">
                                    <div class="specialist__thumbnail">
                                        <img src="${specialist.photo_path}" alt="${specialist.name}">
                                    </div>
                                    <div class="specialist__name">
                                        <h4>${specialist.name}</h4>
                                        <small>${specialist.age} years old</small>
                                    </div>
                                </div>
                                <div class="specialist__info">
                                    <div class="specializations">
                                        ${specializations}
                                    </div>
                                </div>
                                <div class="contact__info">
                                    <div class="more-info__button"><a href="specialist.php?id=${specialist.specialist_id}">More Info</a></div>
                                    <div class="contact__button" data-name="${specialist.name}" data-phone="${specialist.phone}" data-email="${specialist.email}">Contact</div>
                                </div>
                            `;
                            container.appendChild(specialistCard);
                        });

                        // Add event listeners to contact buttons to show modal
                        addContactButtonHandlers();
                    } else {
                        // No specialists found
                        const noSpecialistsMessage = document.createElement('div');
                        noSpecialistsMessage.classList.add('no-specialists');
                        noSpecialistsMessage.textContent = 'No specialists found for this category.';
                        container.appendChild(noSpecialistsMessage);
                    }
                })
                .catch(error => console.error('Error fetching specialists:', error));
        });
    });
}

function addContactButtonHandlers() {
    document.querySelectorAll('.contact__button').forEach(contactButton => {
        contactButton.removeEventListener('click', handleContactButtonClick); // Ensure no duplicate listeners
        contactButton.addEventListener('click', handleContactButtonClick);
    });
}

function handleContactButtonClick(event) {
    const name = event.currentTarget.getAttribute('data-name');
    const phone = event.currentTarget.getAttribute('data-phone');
    const email = event.currentTarget.getAttribute('data-email');
    showModal(name, phone, email);
}

// Function to display the modal with contact information
function showModal(name, phone, email) {
    const modal = document.createElement('div');
    modal.classList.add('contact-modal');
    modal.innerHTML = `
        <div class="contact-modal-content">
            <span class="close">&times;</span>
            <h4>${name}</h4>
            <p>Contact Number: ${phone}</p>
            <p>Email: ${email}</p>
        </div>
    `;
    document.body.appendChild(modal);

    // Close modal when clicking on close button or outside the modal
    modal.querySelector('.close').addEventListener('click', () => {
        modal.remove();
    });
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.remove();
        }
    });
}