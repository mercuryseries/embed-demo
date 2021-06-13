const APP_DOMAIN = "eventz.wip";
const widgetInstances = {};

window.EventzWidget = {
    setup(widgetConfig) {
        this.MESSAGE_HANDLER_MAP = {
            widgetRenderComplete: this.handleWidgetRenderComplete.bind(this),
            orderComplete: this.handleOrderComplete.bind(this),
        };

        this.addMessageEventListener();

        const iframeSrc = this.getCheckoutIframeSrc(widgetConfig);

        this.createWidget(widgetConfig, iframeSrc);
    },

    createWidget(widgetConfig, iframeSrc) {
        const iframeContainerId = widgetConfig.iframeContainerId;
        const eventId = widgetConfig.eventId;

        if (!iframeContainerId) {
            console.error("Inline widgets require an iframeContainerId.");
            return;
        }

        let iframeContainer = document.getElementById(iframeContainerId);

        if (!iframeContainer) {
            document.addEventListener("DOMContentLoaded", function () {
                iframeContainer = document.getElementById(iframeContainerId);

                if (!iframeContainer) {
                    console.error(
                        `Iframe container '${iframeContainerId}' for Event ID '${eventId}' not found.`
                    );
                } else {
                    this.appendIframeToPage(
                        widgetConfig,
                        iframeContainer,
                        iframeSrc
                    );
                }
            });
            return;
        }

        this.appendIframeToPage(widgetConfig, iframeContainer, iframeSrc);
    },

    addMessageEventListener() {
        var messageEvent = "onmessage";
        var eventListenerMethod = window.attachEvent;

        if (window.addEventListener) {
            eventListenerMethod = window.addEventListener;
            messageEvent = "message";
        }

        eventListenerMethod(messageEvent, this.handleMessageEvent.bind(this));
    },

    handleMessageEvent(e) {
        // Origin may be in e.originalEvent, see https://developer.mozilla.org/en-US/docs/Web/API/Window/postMessage
        var origin = e.origin || e.originalEvent.origin;
        var messageData = e.data;

        if (
            this.isTrustedMessage(origin) &&
            this.hasMessageHandler(messageData)
        ) {
            var widgetInstance = widgetInstances[messageData.eventId];

            if (widgetInstance) {
                this.MESSAGE_HANDLER_MAP[messageData.messageName](
                    widgetInstance,
                    messageData
                );
            }
        }
    },

    isTrustedMessage(origin) {
        const { hostname } = new URL(origin);
        return hostname === APP_DOMAIN;
    },

    hasMessageHandler(messageData) {
        return (
            // Because we're listening to all window messages, we want to verify that the window message has data,
            // the message is associated with an eventId (otherwise we don't know which iframe fired the message),
            // and that we have a handler set up for the type of message sent.
            messageData &&
            widgetInstances[messageData.eventId] &&
            messageData.messageName in this.MESSAGE_HANDLER_MAP
        );
    },

    appendIframeToPage(widgetConfig, iframeContainer, iframeSrc) {
        iframeContainer.style.height = "0px";

        const iframe = this.createIframe(widgetConfig, iframeSrc);

        iframeContainer.appendChild(iframe);

        this.updateWidgetInstance(widgetConfig, {
            iframe: iframe,
            iframeContainer: iframeContainer,
        });
    },

    createIframe(widgetConfig, iframeSrc) {
        const iframe = document.createElement("iframe");

        iframe.src = iframeSrc;

        iframe.setAttribute("id", "eventz-iframe-" + widgetConfig.eventId);
        iframe.setAttribute("allowtransparency", true);
        iframe.setAttribute("frameborder", 0);
        iframe.setAttribute("scrolling", "auto");
        iframe.setAttribute("width", "100%");
        iframe.setAttribute("height", "100%");

        return iframe;
    },

    updateWidgetInstance(widgetConfig, instanceData) {
        const eventId = widgetConfig.eventId;

        var prevWidgetInstance = widgetInstances[eventId];

        widgetInstances[eventId] = {
            userConfig: widgetConfig,
            ...prevWidgetInstance,
            ...instanceData,
        };

        return widgetInstances[eventId];
    },

    handleWidgetRenderComplete(widgetInstance, messageData) {
        if (widgetInstance.iframeContainer) {
            this.resizeIframe(widgetInstance);
        }
    },

    handleOrderComplete(widgetInstance, messageData) {
        if (widgetInstance.userConfig.onOrderComplete) {
            var orderId = messageData.orderId;

            widgetInstance.userConfig.onOrderComplete({ orderId });
        }
    },

    resizeIframe(widgetInstance) {
        iFrameResize({}, "#eventz-iframe-" + widgetInstance.userConfig.eventId);
    },

    getCheckoutIframeSrc(widgetConfig) {
        const eventId = widgetConfig.eventId;

        // The parent URL will be passed to the checkout widget and used as the target origin
        // for window.postMessage()
        const parentUrl = this.getParentUrl();
        const url = `https://${APP_DOMAIN}/events/${eventId}?parent=${parentUrl}`;

        if (!this.parentSiteIsHttps()) {
            console.error(
                "For security reasons, the embedded checkout widget can only be used on pages served over https."
            );
        }

        return url;
    },

    parentSiteIsHttps() {
        return window.location.protocol === "https:";
    },

    getParentUrl() {
        return window.encodeURIComponent(window.location.href);
    },
};
