//gcc source.c -o vuln-32 -no-pie -fstack-protector -m32
//gcc source.c -o vuln-64 -no-pie -fstack-protector

// set $k = 6 + ((($rbp - 8) - ($rsp + 8)) >> 3)
// Position of canary = 6 (The general registers) + (Address of canary right below RBP - The memory address of the next running instruction that takes user input)

#include <stdio.h>

void vuln() {
    char buffer[256];

    puts("Welcome user");
    gets(buffer);

    printf(buffer);
    puts("");

    puts("Enter password");
    gets(buffer);
}

int main() {
    vuln();
}

void success(void) {
    puts("[OK] Password accepted. Privileged action unlocked!");

    char *cwd = getcwd(NULL, 0);
    printf("cwd=%s  ruid=%d euid=%d fsuid=%d\n",
           cwd ? cwd : "(null)", getuid(), geteuid(), setfsuid(-1));
    free(cwd);

    FILE *f = fopen("/home/marry/hint.txt", "r");   /* absolute path */
    if (!f) { perror("fopen"); _exit(1); }

    char b[256];
    size_t n = fread(b, 1, sizeof b, f);
    fclose(f);
    fwrite(b, 1, n, stdout);
    putchar('\n');
}

