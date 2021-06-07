# Twitter-clone

## セットアップ

#### .envを作成
```shell
cp .env.example .env
```

#### composerを導入してインストール
```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php74-composer:latest \
    composer install --ignore-platform-reqs
```

#### APP_KEYを生成
```shell
./vendor/bin/sail artisan key:generate
```

#### 環境立ち上げ
```shell
./vendor/bin/sail up
```
※ `-d`オプションをつけるとバックグラウンドで起動できる
