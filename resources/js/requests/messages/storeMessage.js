const storeMessage = async ({
    content,
    onStoreMessage,
    onStoreMessageError,
}) => {
    const response = await fetch('/api/messages', {
        method: 'post',
        headers: {
            'Content-Type': 'application/json; charset=utf-8',
        },
        body: JSON.stringify({ content }),
    });
    if (response.ok) {
        const { data } = await response.json();
        onStoreMessage(data);
    } else {
        if (response.status === 400) {
            const { errors } = await response.json();
            onStoreMessageError(errors);
        } else {
            alert('予期せぬエラーが発生しました。');
        }
    }
};
window.storeMessage = storeMessage;
