# lck: keep your secrets safe

Allows you to safely store your sensitive files in your git repositories.

## Why?

In you git project, do you have any files that contain sensitive information,
such as database passwords, API keys, personnal information, etc?

Then you should make sure to **never** commit them...

... Yet you need somehow to store them somewhere,
and share them with your team,
and ship them to your different deployment environments.

That's where the `lck` commands come in handy.

## How?

Using [Public Key Cryptography](https://en.wikipedia.org/wiki/Public-key_cryptography),
it is possible to encrypt your sensitive files and commit them safely in your
git projects.

How does this work, in simple terms?

First you need to generate a pair of keys:

* the public "encrypting" key: commit and share it
* the private "decrypting" key: keep it secret and safe

You can then use the public key to encrypt your sensitive files.
The encrypted sensitive files can be commited and share safely!

Finally, use the private key to decrypt them and get access to the
sensitive files.

This means that now, instead of having to manage a ton of sensitive files
outside of your git repository, you just have to keep out a single one:
the private key.

Oh, hang on, your private key has been compromised?
Don't panic, all you need to do is rotate it: decrypt all the files,
throw away the keys, generate news ones and re-encrypt everything.

### Under the hood: Sodium

To achive this, `lck` relies on Sodium's `crypto_box_seal` API.

[Sodium](https://github.com/jedisct1/libsodium) is a crypto library which is
available in PHP as a core extension. Its `crypto_box_seal` API allows for
Unauthenticated Asymmetric encryption / decryption, which is exactly what we've
described in the above section.

Here's a code snippet demonstrating its usage:

```php
$privateDecryptingKey = sodium_crypto_box_keypair();
$publicEncryptingKey = sodium_crypto_box_publickey(
    $keyPair,
);

$messageToEncrypt = 'Super secret.';
$encryptedMessage = sodium_crypto_box_seal(
    $messageToEncrypt,
    $publicEncryptingKey,
);

$decryptedMessage = sodium_crypto_box_seal_open(
    $encryptedMessage,
    $privateDecryptingKey,
);
```

> **Note**:
> `sodium_crypto_box_keypair()` returns a "key pair" string containing a unified
> X25519 secret key and corresponding X25519 public key.
> The public encrypting key is extracted from the "key pair" using
> `sodium_crypto_box_publickey`.
> The "key pair" itself is then used as a private decrypting key
> (the X25519 secret key from the "key pair" doesn't need to be extracted for
> unauthenticated asymmetric encryption).

[See this article for reference](https://php.watch/articles/modern-php-encryption-decryption-sodium#asym-unauth).

## What?

TODO:

* `lck:generate-keys`:
  * [ ] generates new pair of encrypting/decrypting keys
* `lck:encrypt`:
  * [ ] encrypt single file
  * [ ] encrypt directory
  * [ ] save path to encrypted file
* `lck:decrypt`:
  * [ ] decrypt single file
  * [ ] decrypt directory
* `lck-rotate-keys`:
  * [ ] decrypts files (using saved paths)
  * [ ] generates new pair of encrypting/decrypting keys
  * [ ] encrypts files (using new pair of keys)
