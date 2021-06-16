const fetchMessage = async ({
    link,
    onFetchMessage,
}) => {
    const response = await fetch(link);
    if (response.ok) {
        const { data, links } = await response.json();
        onFetchMessage({ data, links });
    } else {
        alert('メッセージ読み込みに失敗しました。もう一度やり直してください。')
    }
};
window.fetchMessage = fetchMessage;
