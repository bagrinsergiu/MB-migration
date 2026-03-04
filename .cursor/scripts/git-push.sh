#!/usr/bin/env bash
# git-push.sh — create branch, commit, push
set -e

ROOT="$(git rev-parse --show-toplevel)"
cd "$ROOT"

# ── 1. Show current status ──────────────────────────────────────────────────
echo ""
echo "=== Changed files ==="
git status --short
echo ""

CHANGED_FILES=$(git diff --name-only HEAD 2>/dev/null; git ls-files --others --exclude-standard)
FILE_COUNT=$(echo "$CHANGED_FILES" | grep -c . || true)

# ── 2. Branch name ──────────────────────────────────────────────────────────
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
TODAY=$(date +%Y-%m-%d)

# Suggest a branch name from changed dirs/files
SUGGESTION=$(echo "$CHANGED_FILES" | head -3 \
  | sed 's|lib/MBMigration/||g; s|packages/elements/src/||g' \
  | sed 's|/|-|g; s|\.php||g; s|\.ts||g' \
  | tr '[:upper:]' '[:lower:]' \
  | tr '\n' '_' \
  | sed 's/_$//' \
  | cut -c1-40)
SUGGESTION="feature/${TODAY}_${SUGGESTION}"

echo "Current branch : $CURRENT_BRANCH"
printf "New branch name [%s]: " "$SUGGESTION"
read -r BRANCH_NAME
BRANCH_NAME="${BRANCH_NAME:-$SUGGESTION}"
BRANCH_NAME=$(echo "$BRANCH_NAME" | tr ' ' '-' | tr '[:upper:]' '[:lower:]')

# ── 3. Create branch ────────────────────────────────────────────────────────
if git show-ref --verify --quiet "refs/heads/$BRANCH_NAME"; then
  echo "Branch '$BRANCH_NAME' already exists — switching to it."
  git checkout "$BRANCH_NAME"
else
  git checkout -b "$BRANCH_NAME"
  echo "Created branch '$BRANCH_NAME'."
fi

# ── 4. Stage all changes ────────────────────────────────────────────────────
git add -A
echo ""
echo "=== Staged ==="
git diff --cached --stat
echo ""

# ── 5. Commit message ───────────────────────────────────────────────────────
# Auto-suggest: list unique top-level areas changed
AREAS=$(git diff --cached --name-only \
  | sed 's|lib/MBMigration/Builder/Layout/Theme/\([^/]*\)/.*|\1|;
         s|lib/MBMigration/Builder/Layout/Common/.*|Common|;
         s|packages/elements/src/\([^/]*\)/.*|elements/\1|;
         s|packages/\([^/]*\)/.*|\1|' \
  | sort -u | tr '\n' ', ' | sed 's/, $//')

AUTO_MSG="fix(${AREAS}): update $(echo "$CHANGED_FILES" | wc -l | tr -d ' ') files"

printf "Commit message [%s]: " "$AUTO_MSG"
read -r COMMIT_MSG
COMMIT_MSG="${COMMIT_MSG:-$AUTO_MSG}"

git commit -m "$COMMIT_MSG"

# ── 6. Push ─────────────────────────────────────────────────────────────────
echo ""
echo "Pushing '$BRANCH_NAME' to origin..."
git push -u origin "$BRANCH_NAME"

echo ""
echo "Done! Branch '$BRANCH_NAME' pushed."
echo "PR URL hint: $(git remote get-url origin | sed 's/\.git$//')/compare/$BRANCH_NAME"
