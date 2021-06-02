#= require trix/controllers/attachment_editor_controller

{defer, escapeHTML, handleEvent, makeElement, tagName} = Trix
{keyNames} = Trix.InputController
{lang, css} = Trix.config

class Trix.AttachmentEditorController extends Trix.BasicObject
  constructor: (@attachmentPiece, @element, @container, @options = {}) ->
    {@attachment} = @attachmentPiece
    @element = @element.firstChild if tagName(@element) is "a"
    @install()

  undoable = (fn) -> ->
    commands = fn.apply(this, arguments)
    commands.do()
    @undos ?= []
    @undos.push(commands.undo)

  install: ->
    @makeElementMutable()
    @addToolbar()
    if @attachment.isPreviewable()
      @installCaptionEditor()

  uninstall: ->
    @savePendingCaption()
    undo() while undo = @undos.pop()
    @delegate?.didUninstallAttachmentEditor(this)

  # Private

  savePendingCaption: ->
    if @pendingCaption?
      caption = @pendingCaption
      @pendingCaption = null
      if caption
        @delegate?.attachmentEditorDidRequestUpdatingAttributesForAttachment?({caption}, @attachment)
      else
        @delegate?.attachmentEditorDidRequestRemovingAttributeForAttachment?("caption", @attachment)

  # Installing and uninstalling

  makeElementMutable: undoable ->
    do: => @element.dataset.trixMutable = true
    undo: => delete @element.dataset.trixMutable

  addToolbar: undoable ->
    element = makeElement(tagName: "div", className: css.attachmentToolbar, data: trixMutable: true)
    element.innerHTML = """
      <div class="trix-button-row">
        <span class="trix-button-group trix-button-group--actions">
          <button type="button" data-trix-action="remove" class="trix-button trix-button--remove" title="#{lang.remove}">#{lang.remove}</button>
        </span>
      </div>
    """

    if @attachment.isPreviewable()
      name = escapeHTML(@attachment.getFilename())
      size = escapeHTML(@attachment.getFormattedFilesize())

      element.innerHTML += """
        <div class="#{css.attachmentMetadataContainer}">
          <span class="#{css.attachmentMetadata}">
            <span class="#{css.attachmentName}" title="#{name}">#{name}</span>
            <span class="#{css.attachmentSize}">#{size}</span>
          </span>
        </div>
      """

    handleEvent("click", onElement: element, withCallback: @didClickToolbar)
    handleEvent("click", onElement: element, matchingSelector: "[data-trix-action]", withCallback: @didClickActionButton)

    do: => @element.appendChild(element)
    undo: => Trix.removeNode(element)

  installCaptionEditor: undoable ->
    textarea = makeElement
      tagName: "textarea"
      className: css.attachmentCaptionEditor
      attributes: placeholder: lang.captionPlaceholder
      data: trixMutable: true
    textarea.value = @attachmentPiece.getCaption()

    textareaClone = textarea.cloneNode()
    textareaClone.classList.add("trix-autoresize-clone")
    textareaClone.tabIndex = -1

    autoresize = ->
      textareaClone.value = textarea.value
      textarea.style.height = textareaClone.scrollHeight + "px"

    handleEvent("input", onElement: textarea, withCallback: autoresize)
    handleEvent("input", onElement: textarea, withCallback: @didInputCaption)
    handleEvent("keydown", onElement: textarea, withCallback: @didKeyDownCaption)
    handleEvent("change", onElement: textarea, withCallback: @didChangeCaption)
    handleEvent("blur", onElement: textarea, withCallback: @didBlurCaption)

    figcaption = @element.querySelector("figcaption")
    editingFigcaption = figcaption.cloneNode()

    do: =>
      figcaption.style.display = "none"
      editingFigcaption.appendChild(textarea)
      editingFigcaption.appendChild(textareaClone)
      editingFigcaption.classList.add("#{css.attachmentCaption}--editing")
      figcaption.parentElement.insertBefore(editingFigcaption, figcaption)
      autoresize()
      if @options.editCaption
        defer -> textarea.focus()
    undo: ->
      Trix.removeNode(editingFigcaption)
      figcaption.style.display = null

  # Event handlers

  didClickToolbar: (event) =>
    event.preventDefault()
    event.stopPropagation()

  didClickActionButton: (event) =>
    action = event.target.getAttribute("data-trix-action")
    switch action
      when "remove"
        @delegate?.attachmentEditorDidRequestRemovalOfAttachment(@attachment)

  didKeyDownCaption: (event) =>
    if keyNames[event.keyCode] is "return"
      event.preventDefault()
      @savePendingCaption()
      @delegate?.attachmentEditorDidRequestDeselectingAttachment?(@attachment)

  didInputCaption: (event) =>
    @pendingCaption = event.target.value.replace(/\s/g, " ").trim()

  didChangeCaption: (event) =>
    @savePendingCaption()

  didBlurCaption: (event) =>
    @savePendingCaption()
