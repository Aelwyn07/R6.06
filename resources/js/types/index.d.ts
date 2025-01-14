interface Role {
  name: string;
}

interface User {
  id: number;
  name: string;
  email: string;
  role: Role;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};
