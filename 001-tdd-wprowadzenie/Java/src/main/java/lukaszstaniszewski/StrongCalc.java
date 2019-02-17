package lukaszstaniszewski;

/**
 * The type Strong calc.
 */
public class StrongCalc {

    /**
     * Calc int.
     *
     * @param strong the strong
     * @return the int
     * @throws Exception the exception
     */
    public int calc(int strong) throws Exception{
        if (strong <= 0) {
            throw new Exception();
        }

        int strongResult = 1;

        for (int i = 1; i <= strong; ++i) {
            strongResult *= i;
        }

        return strongResult;
    }

}
