const updateMessage = async ({
    messageId,
    content,
    onUpdateMessage,
    onUpdateMessageError,
}) => {
    const response = await fetch(`/api/messages/${messageId}`, {
        method: 'put',
        headers: {
            'Content-Type': 'application/json; charset=utf-8',
        },
        body: JSON.stringify({ content }),
    });
    if (response.ok) {
        const { data } = await response.json();
        onUpdateMessage(data);
    } else {
        if (response.status === 400) {
            const { errors } = await response.json();
            onUpdateMessageError(errors);
        } else {
            alert('予期せぬエラーが発生しました。');
        }
    }
};
window.updateMessage = updateMessage;
