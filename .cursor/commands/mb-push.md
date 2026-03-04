# /mb-push

Execute this git workflow using shell tools. No narration between steps — just run and show relevant output.

---

## STEP 1 — Get changed files

Run:
```
git diff --name-only HEAD
```

If nothing changed → say "No changes to commit" and stop.

Save this list as CHANGED_FILES.

---

## STEP 2 — Ask: together or split?

Show the changed files list and ask ONE question:

> **Changed files:**
> - file1
> - file2
> - ...
>
> Push as **[1] single branch** or **[2] split by task**?

Wait for reply: `1` = single branch (go to FLOW_SINGLE), `2` = split mode (go to FLOW_SPLIT).

---

## FLOW_SINGLE — Push everything as one branch

Same as before:

1. Suggest branch name: `feature/YYYY-MM-DD-<scope>` based on changed paths
2. Ask: **"Branch name? [suggestion]"** — `y`/Enter = accept
3. `git checkout -b <branch>`
4. `git diff --name-only HEAD | xargs git add` (tracked files only, NO `git add -A`)
5. Suggest commit message: `<type>(<scope>): <description>` — analyse diff for type/scope
6. Ask: **"Commit message? [suggestion]"** — `y`/Enter = accept
7. `git commit -m "<message>"` + `git push -u origin <branch>`
8. `git checkout main`
9. Show: commit hash + PR link `https://github.com/bagrinsergiu/MB-migration/compare/<branch>`

---

## FLOW_SPLIT — Smart split by tasks

### 2a. Read task files

Run:
```
ls -t task/*/task.md | head -15
```

For each task file found, read its content and extract:
- Task ID (from folder name, e.g. `010`)
- Task date (from folder name, e.g. `2026-02-23`)
- File paths mentioned (look for backtick paths like `` `lib/...` `` or `- lib/...` lines)
- Short description (first meaningful sentence from task)

### 2b. Match changed files to tasks

For each file in CHANGED_FILES:
- Check if it's mentioned in any task file (partial path match is enough)
- Assign it to the most recent matching task
- If no match → put in group "misc"

Also group `.cursor/` files together as "chore/agents-tools" automatically (no task needed).

### 2c. Show proposed groups and ask confirmation

Show all groups:

```
Proposed split:

── Group 1: task/010 — Hope mobile reverse order
   Branch: fix/2026-02-23-hope-mobile-order
   • lib/.../Hope/Elements/Text/LeftMedia.php
   • lib/.../Hope/Elements/Text/RightMediaElement.php

── Group 2: task/006 + task/012 — Hope button & Head alignment
   Branch: fix/2026-02-23-hope-button-head
   • lib/.../Common/Concern/RichTextAble.php
   • lib/.../Hope/Elements/Head.php
   • lib/.../Hope/blocksKit.json
   • packages/elements/src/Text/models/Button/index.ts
   • packages/elements/src/Text/models/Button/utils/getModel.ts
   • packages/elements/src/Text/utils/common/index.ts

── Group 3: chore — cursor agents update
   Branch: chore/2026-02-23-agents
   • .cursor/agents/task-decomposer.md
   • .cursor/agents/task-worker.md
```

Ask:
> **OK? Or adjust?**
> - `y` / Enter — push all groups as shown
> - `merge 1+2` — merge groups 1 and 2
> - `skip 3` — skip group 3
> - Describe any other adjustment in plain text

Apply user adjustments, then confirm final groups before proceeding.

### 2d. Push each group sequentially

For each confirmed group (in order):

1. `git checkout main` (ensure clean base)
2. `git checkout -b <branch-name>`
3. Stage ONLY files in this group: `git add <file1> <file2> ...`
4. Build commit message from task description:
   - type: `fix` / `feat` / `refactor` / `chore`
   - scope: main module from file paths
   - description: short sentence from task.md
5. `git commit -m "<message>"`
6. `git push -u origin <branch-name>`
7. Report: `✓ Group N pushed → <PR link>`

After all groups → `git checkout main`

### 2e. Final summary

Show a table:

```
Done! Pushed N branches:

  Branch                              PR
  fix/2026-02-23-hope-mobile-order    https://github.com/.../compare/...
  fix/2026-02-23-hope-button-head     https://github.com/.../compare/...
  chore/2026-02-23-agents             https://github.com/.../compare/...

Back on: main
```
