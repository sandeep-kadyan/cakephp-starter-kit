# CakePHP Fake Data Generation Command

This command allows you to generate fake records for any model in your CakePHP application, using dynamic schema inspection and customizable special field handling.

## Features

- **Dynamic model support:** No hardcoded model list. The command works for any model with a Table class.
- **Schema-aware:** Generates fake data based on column names and types.
- **Special fields:** Handles fields like `password`, `parent_id`, `*_at`, `image`, `file`, and binary/blob types as special by default.
- **Custom special fields:** You can specify additional special fields via a command-line option.
- **Foreign key support:** For fields ending in `_id`, the command tries to use an existing record from the related table.
- **Dry run mode:** Preview generated data without saving.
- **Model listing:** List available models for fake data generation.

## Usage

### List available models

```bash
bin/cake fake --list-models
```

### Generate fake data for a model

```bash
bin/cake fake <model> <count>
```

Example:

```bash
bin/cake fake users 10
```

### Preview (dry run) fake data

```bash
bin/cake fake users 5 --dry-run
```

### Specify custom special fields

```bash
bin/cake fake users 10 --special-fields=avatar,logo,thumbnail
```

### Options

- `--list-models, -l` : List available models for fake data generation.
- `--dry-run, -d` : Show what would be generated without saving to the database.
- `--special-fields, -s` : Comma-separated list of additional special fields (e.g., `avatar,logo`).

## How it works

- The command inspects the model's schema to determine columns and types.
- For each column, it generates a value based on the type and name:
  - **password**: Hashed password
  - **parent_id, *_id**: Uses an existing record from the related table if available
  - **image, file, binary/blob**: Generates a fake file path or binary data
  - ***_at**: Generates a random datetime
  - **email, username, name, url, token, secret, recovery_codes**: Generates realistic values
  - **Other types**: Uses sensible defaults
- You can override or add to the special field detection using the `--special-fields` option.

## Extending

- To support more models, ensure you have a Table class for each model in `src/Model/Table`.
- To add more advanced field detection, edit `src/Service/FakeService.php`.

## Example

```bash
bin/cake fake activities 5 --special-fields=avatar,logo
```

This will generate 5 fake activity records, treating `avatar` and `logo` as special fields (e.g., generating fake file/image data for them).

**Maintainer:** Sandeep Kadyan (and contributors) 
