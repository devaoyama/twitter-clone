const destroyMessage = async ({
    messageId,
    onDestroyMessage,
}) => {
    const response = await fetch(`/api/messages/${messageId}`, {
        method: 'delete',
    });
    if (response.ok) {
        const { data } = await response.json();
        onDestroyMessage(data);
    } else {
        alert('予期せぬエラーが発生しました。');
    }
};
window.destroyMessage = destroyMessage;
