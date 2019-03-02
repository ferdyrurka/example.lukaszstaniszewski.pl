package lukaszstaniszewski.util;

/**
 * The type Email.
 */
public class Email implements EmailInterface {

    @Override
    public boolean send(String email) throws Exception {
        if (!email.matches("^([a-z|A-Z|0-9]){1,24}@([a-z|A-Z|0-9]){1,64}.([a-z]){2,6}$")) {
            throw new Exception("Email is invalid");
        }

        // Send mail

        return  true;
    }
}
