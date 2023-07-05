wp.blocks.registerBlockType('openai/custom-block', {
    title: 'Open AI',
    icon: 'cloud',
    category: 'widgets',
    attributes: {
         
    },
    edit: function(props){
        return /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h3", null, "Open AI Chat"), /*#__PURE__*/React.createElement("div", {
          class: "chat-window"
        }, "The following is a conversation with an AI assistant. The assistant is helpful, creative, clever, and very friendly."), /*#__PURE__*/React.createElement("form", {
          action: "#",
          id: "ai-chat",
          method: "post",
          class: "my-lg"
        }, /*#__PURE__*/React.createElement("p", null, "Message:"), /*#__PURE__*/React.createElement("div", {
          class: "input-container"
        }, /*#__PURE__*/React.createElement("input", {
          type: "text",
          class: "input input-lg",
          name: "message",
          disabled: true
        })), /*#__PURE__*/React.createElement("div", {
          class: "input-container"
        }, /*#__PURE__*/React.createElement("input", {
          type: "submit",
          class: "btn btn-lg btn-secondary d-block",
          value: "Send",
          disabled: true
        }))));;
    },
    save: function(props){
        return /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h3", null, "Open AI Chat"), /*#__PURE__*/React.createElement("div", {
          class: "chat-window"
        }, "The following is a conversation with an AI assistant. The assistant is helpful, creative, clever, and very friendly.\n", /*#__PURE__*/React.createElement("b", null, "AI: "), "Hello, I'm Open AI. Ask me something."), /*#__PURE__*/React.createElement("form", {
          action: "#",
          method: "post",
          class: "my-lg ai-chat"
        }, /*#__PURE__*/React.createElement("p", null, "Message:"), /*#__PURE__*/React.createElement("div", {
          class: "input-container"
        }, /*#__PURE__*/React.createElement("input", {
          type: "text",
          class: "input input-lg",
          name: "message",
          required: true
        })), /*#__PURE__*/React.createElement("div", {
          class: "input-container"
        }, /*#__PURE__*/React.createElement("input", {
          type: "submit",
          class: "btn btn-lg btn-secondary d-block",
          value: "Send"
        }))));;
    }
})