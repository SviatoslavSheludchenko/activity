function errorModal(message) {
    document.body.classList.add('model-open');

    let div = document.createElement('div');
    div.id = 'errors-modal';
    div.className = 'modal fade show';
    div.style = 'display: block';
    div.innerHTML = '<div class="modal-dialog">\n' +
        '            <div class="modal-content">\n' +
        '                <div class="modal-body" style="font-size: 1.2rem">' + message + '</div>\n' +
        '                <div class="modal-footer" style="border: none">\n' +
        '                    <button type="button" class="btn btn-secondary btn-remove" data-dismiss="modal">Close</button>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '        </div>';

    $(div).on('click', '.btn-remove', function(e) {
        e.target.closest('#errors-modal').remove();
        background.remove();
    });
    let background = document.createElement('div');
    background.className = 'modal-backdrop fade show';

    document.body.appendChild(div);
    document.body.appendChild(background);
}

window.errorModal = errorModal;

// Function for multiple forms - client update brands, etc.
function createMultipleFormsEdit(firstFieldsetFirstDivSelector) {
    let formSelector = firstFieldsetFirstDivSelector;
    $(formSelector).closest('form').addClass('multiple-forms-edit');


    var $addLink = $('<a href="#" class="action-button add-element-link"><span class="fa fa-plus-circle"></span></a>');
    var $addContainer = $('<div></div>');
    let removeLinkHtml = '<a href="#" class="action-button remove-element"><span class="fa fa-trash"></span></a>';

    var $collectionHolder = $(formSelector);

    $collectionHolder.append($addContainer);
    $collectionHolder.find('option[value="0"]').remove();

    $collectionHolder.data('index', getFormsCount());
    addBrandForm($collectionHolder, $addContainer);

    $collectionHolder.on('click', '.add-element-link', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        addBrandForm($collectionHolder, $addContainer);
    });

    $collectionHolder.on('click', '.remove-element', function(e) {
        e.preventDefault();
        $(this).closest('.form-group').remove();

        return false;
    });

    function addBrandForm($collectionHolder, $addSettingContainer) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '$$name$$' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        $newForm = $(newForm);

        $addSettingContainer.before($newForm);
        addButtons();
    }

    function addButtons() {
        $(`${formSelector} > fieldset.form-group > div`).each(function(key, element) {
            $(element).find('.action-button').remove();
            if (key + 1 === getFormsCount()) {
                $(element).append($addLink);
            } else {
                $(element).append(removeLinkHtml);
            }
        });
    }

    function getFormsCount() {
        return $collectionHolder.find('fieldset').length;
    }
}

window.createMultipleFormsEdit = createMultipleFormsEdit;
