document.addEventListener('DOMContentLoaded', function () {
    const addButton = document.getElementById('add');
    const clearAllButton = document.getElementById('clear__all');
    const createSetButton = document.getElementById('create__set');
    const selectContainer = document.getElementById('select__specializations');
    const specialistsContainer = document.querySelector('.specialists__container');
    
    addButton.addEventListener('click', addNewSelect);
    clearAllButton.addEventListener('click', clearAllSelects);
    createSetButton.addEventListener('click', createSet);

    function addNewSelect() {
        const selectWrapper = document.createElement('div');
        selectWrapper.classList.add('select__specialization');
        const selectElement = document.createElement('select');
        selectElement.innerHTML = `
            <option value="1">Surgeon</option>
            <option value="2">Traumatologist</option>
            <option value="3">Orthopedist</option>
            <option value="4">Neurosurgeon</option>
            <option value="5">Therapist</option>
            <option value="6">Neurologist</option>
            <option value="7">Cardiologist</option>
            <option value="8">Physiotherapist</option>
            <option value="9">Dentist</option>
        `;
        selectWrapper.appendChild(selectElement);
        selectContainer.appendChild(selectWrapper);
    }

    function clearAllSelects() {
        selectContainer.innerHTML = '';
        specialistsContainer.innerHTML = '';
    }

    function createSet() {
        const selectedSpecializations = Array.from(selectContainer.querySelectorAll('select')).map(select => select.value);
        fetchSpecialistsBySpecializations(selectedSpecializations);
    }

    function fetchSpecialistsBySpecializations(specializations) {
        fetch('logic/fetch_specialists.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ specializations })
        })
        .then(response => response.json())
        .then(data => {
            const specialists = formMinimalMedicalTeam(data, specializations);
            displaySpecialists(specialists, specializations);
        })
        .catch(error => console.error('Error fetching specialists:', error));
    }

    function formMinimalMedicalTeam(data, requiredSpecializations) {
        const specializationCount = {};
        requiredSpecializations.forEach(specId => {
            specializationCount[specId] = 0;
        });
        const selectedSpecialists = [];

        const specialists = Object.values(data);
        specialists.sort((a, b) => b.specializations.length - a.specializations.length);

        for (const specialist of specialists) {
            let neededSpecialist = false;
            for (const spec of specialist.specializations) {
                if (requiredSpecializations.includes(spec.id.toString()) && specializationCount[spec.id] < 2) {
                    neededSpecialist = true;
                    specializationCount[spec.id] += 1;
                }
            }
            if (neededSpecialist) {
                selectedSpecialists.push(specialist);
            }
            if (Object.values(specializationCount).every(count => count >= 2)) {
                break;
            }
        }

        return selectedSpecialists;
    }

    function displaySpecialists(specialists, selectedSpecializations) {
        specialistsContainer.innerHTML = '';
        if (specialists.length > 0) {
            specialists.forEach(specialist => {
                const specialistCard = document.createElement('div');
                specialistCard.classList.add('specialist__card');

                const specializationsHTML = specialist.specializations.map(spec => {
                    const isSelected = selectedSpecializations.includes(spec.id.toString());
                    return `<div class="specialization"><h5${isSelected ? ' style="font-weight: bold;"' : ''}>${spec.name}</h5></div>`;
                }).join('');

                specialistCard.innerHTML = `
                    <div class="specialist__info">
                        <div class="specialist__thumbnail">
                            <img src="${specialist.info.photo_path}" alt="${specialist.info.name}">
                        </div>
                        <div class="specialist__name">
                            <h4>${specialist.info.name}</h4>
                            <small>${specialist.info.age} years old</small>
                        </div>
                    </div>
                    <div class="specialist__info">
                        <div class="specializations">
                            ${specializationsHTML}
                        </div>
                    </div>
                    <div class="contact__info">
                        <div class="more-info__button"><a href="specialist.php?id=${specialist.info.id}">More Info</a></div>
                        <div class="contact__button" data-name="${specialist.info.name}" data-phone="${specialist.info.phone}" data-email="${specialist.info.email}">Contact</div>
                    </div>
                `;
                specialistsContainer.appendChild(specialistCard);
            });
            addContactButtonHandlers();
        } else {
            const noSpecialistsMessage = document.createElement('div');
            noSpecialistsMessage.classList.add('no-specialists');
            noSpecialistsMessage.textContent = 'No specialists found for the selected specializations.';
            specialistsContainer.appendChild(noSpecialistsMessage);
        }
    }

    function addContactButtonHandlers() {
        document.querySelectorAll('.contact__button').forEach(contactButton => {
            contactButton.removeEventListener('click', handleContactButtonClick);
            contactButton.addEventListener('click', handleContactButtonClick);
        });
    }

    function handleContactButtonClick(event) {
        const name = event.currentTarget.getAttribute('data-name');
        const phone = event.currentTarget.getAttribute('data-phone');
        const email = event.currentTarget.getAttribute('data-email');
        showModal(name, phone, email);
    }

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

        modal.querySelector('.close').addEventListener('click', () => modal.remove());
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.remove();
            }
        });
    }
});